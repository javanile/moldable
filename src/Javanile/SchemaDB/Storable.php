<?php
/**
 * 
 *
 */

namespace Javanile\SchemaDB;

use Javanile\SchemaDB\Readable;

class Storable extends Readable implements Notations
{
    /**
     *
     * @var type
     */
    static $__adamant = false;

    /**
     * Construct a storable object
     * with filled fields by values
     *
     *
     */
    public function __construct($values=null)
    {
        //
        $parser = static::getDatabase()->getParser();

        // prepare field values strip schema definitions
        foreach (static::getSchemaFields() as $field) {
            
            //
            $this->{$field} = $parser->getNotationValue($this->{$field});
        }

        //
        $this->fillSchemaFields($values);

        // update related table
        static::applyTable();
    }

    /**
     * Auto-store element method
     *
     * @return type
     */
    public function store($values=null)
    {
        // update values before store
        if (is_array($values)) {

            //
            foreach ($values as $field => $value) {

                //
                $this->{$field} = $value;
            }
        }

        // retrieve primary key
        $key = static::getPrimaryKey();

        // based on primary key store action
        if ($key && $this->{$key}) {
            return $this->storeUpdate();
        } 

        //
        else {
            return $this->storeInsert();
        }
    }  

    /**
     *
     *
     * @return boolean
     */
    public function storeUpdate()
    {
        // update database schema
        static::applyTable();

        //
        $key = static::getPrimaryKey();

        //
        $setArray = array();

        //
        $valuesArray = array();

        //
        $fields = static::getSchemaFields();

        //
        foreach ($fields as $field) {

            //
            if ($field == $key) { continue; }

            //
            $token = ':'.$field;

            //
            $setArray[] = $field.' = '.$token;

            //
            $valuesArray[$token] = $this->{$field};
        }

        //
        $set = implode(',', $setArray);

        //
        $table = static::getTable();

        //
        $index = $this->{$key};

        //
        $sql = "UPDATE {$table} SET {$set} WHERE {$key}='{$index}'";

        //
        static::getDatabase()->execute($sql, $valuesArray);

        //
        if ($key) {
            return $this->{$key};
        }

        //
        else {
            return true;
        }
    }

    /**
     *
     *
     * @param type $force
     * @return boolean
     */
    public function storeInsert($force=false)
    {
        // update table if needed
        static::applyTable();

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

            //
            if (($field == $key || is_null($this->{$field})) && !$force) {
                continue;
            }

            // get current value of attribute of object
            $value = static::insertRelationBefore($this->{$field}, $column);

            //
            $token = ':'.$field;

            //
            $fieldsArray[] = $field;

            //
            $valuesArray[] = $token;

            //
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

            //
            if (!$force && $field == $key) { continue; }

            //
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
     *
     * @param type $value
     */
    private static function insertRelationBefore($value, &$column)
    {
        //
        if (!is_array($value)) {
            return $value;
        }

        //
        switch ($column['Relation']) {

            //
            case '1:1': return static::insertRelationOneToOne($value, $column);

            //
            case '1:*':    return static::insertRelationOneToMany($value, $column);
        }

    }

    /**
     *
     * 
     */
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
     *
     * @param type $value
     */
    private static function insertRelationAfter($value, &$column)
    {
        //
        if (!is_array($value)) {
            return $value;
        }

        //
        switch ($column['Relation']) {

            //
            case '1:*':    return static::insertRelationOneToMany($value, $column);
        }
    }

    /**
     *
     * 
     */
    private static function insertRelationOneToMany($values, &$column)
    {
        //
        $class = $column['Class'];

        //
        foreach($values as $value) {

            //
            $object = new $class($value);

            //
            $index = $object->store();
        }

        //
        return $index;
    }
}
