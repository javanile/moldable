<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable;

class Storable extends Readable
{
    use Model\UpdateApi;
    use Model\DeleteApi;
    use Model\ManageApi;

    /**
     * Configuration array.
     *
     * @var type
     */
    public static $__config = [
        'adamant' => false,
    ];

    /**
     * Construct a storable object
     * with filled fields by values.
     *
     * @param array $values
     */
    public function __construct($values = null)
    {
        parent::__construct();

        $this->values($values);
    }

    /**
     * Prepare field with notation to with default values.
     */
    protected function init()
    {
        static::applySchema();

        $this->initSchemaFields();
    }

    /**
     * Prepare field with notation to with specific values.
     *
     * @param array $values
     */
    protected function values($values)
    {
        static::applySchema();

        $this->initSchemaFields();
        $this->fillSchemaFields($values);
    }

    /**
     * Auto-store element method.
     *
     * @param null|mixed $values
     *
     * @return type
     */
    public function store($values = null)
    {
        static::applySchema();

        // update values before store
        if (is_array($values)) {
            foreach ($values as $field => $value) {
                $this->{$field} = $value;
            }
        }

        // if has primary update else insert
        $key = static::getPrimaryKey();
        if ($key && isset($this->{$key}) && $this->{$key}) {
            return $this->storeUpdate();
        }

        return $this->storeInsert();
    }

    /**
     * @return bool
     */
    public function storeUpdate()
    {
        static::applySchema();

        $key = static::getPrimaryKey();
        $fields = static::getSchemaFields();
        $setArray = [];
        $params = [];

        foreach ($fields as $field) {
            if ($field == $key) {
                continue;
            }

            $token = ':'.$field;
            $setArray[] = '`'.$field.'`'.' = '.$token;
            $params[$token] = $this->{$field};
        }

        $set = implode(',', $setArray);
        $table = static::getTable();
        $index = $this->{$key};
        $sql = "UPDATE {$table} SET {$set} WHERE `{$key}` = '{$index}'";

        static::getDatabase()->execute($sql, $params);

        return $key ? $this->{$key} : true;
    }

    /**
     * @param type $force
     *
     * @return bool
     */
    public function storeInsert($force = false)
    {
        // update table if needed
        static::applySchema();

        // collect field names for sql query
        $fieldsArray = [];

        // collect values for sql query
        $valuesArray = [];

        // collect tokens value for pdo parametric
        $tokensArray = [];

        // get primary field name
        $key = static::getPrimaryKey();

        // get complete fields schema
        $schema = static::getSchema();

        //
        foreach ($schema as $field => &$column) {
            if (($field == $key || is_null($this->{$field})) && !$force) {
                continue;
            }
            // get current value of attribute of object
            $value = static::insertRelationBefore($this->{$field}, $column);
            $token = ':'.$field;
            $fieldsArray[] = '`'.$field.'`';
            $valuesArray[] = $token;
            $tokensArray[$token] = $value;
        }

        //
        $fields = implode(',', $fieldsArray);

        //
        $values = implode(',', $valuesArray);

        //
        $table = static::getTable();

        //
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";

        //
        static::getDatabase()->execute($sql, $tokensArray);

        //
        foreach ($schema as $field => &$column) {
            if (!$force && $field == $key) {
                continue;
            }

            static::insertRelationAfter($this->{$field}, $column);
        }

        //
        if ($key) {
            $index = static::getDatabase()->getLastId();
            $this->{$key} = $index;
        } else {
            $index = static::getMainFieldValue();
        }

        //
        return $index;
    }

    /**
     * @param type  $value
     * @param mixed $column
     */
    private static function insertRelationBefore($value, &$column)
    {
        //
        if (!is_array($value)) {
            return $value;
        }

        //
        switch ($column['Relation']) {
            case '1:1':
                return static::insertRelationOneToOne($value, $column);
            case '1:*':
                return static::insertRelationOneToMany($value, $column);
        }
    }

    private static function insertRelationOneToOne($value, &$column)
    {
        //
        $class = $column['Class'];

        //
        $object = new $class($value);

        //
        $index = $object->store();

        //
        return $index;
    }

    /**
     * @param type  $value
     * @param mixed $column
     */
    private static function insertRelationAfter($value, &$column)
    {
        //
        if (!is_array($value)) {
            return $value;
        }

        //
        switch ($column['Relation']) {
            case '1:*':
                return static::insertRelationOneToMany($value, $column);
        }
    }

    private static function insertRelationOneToMany($values, &$column)
    {
        $class = $column['Class'];

        foreach ($values as $value) {
            $object = new $class($value);
            $index = $object->store();
        }

        return $index;
    }
}
