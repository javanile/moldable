<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait FieldApi
{
    /**
     * Initialize all fields in class.
     */
    protected function initSchemaFields()
    {
        $schema = static::getSchemaFields();
        $parser = static::getDatabase()->getParser();

        // prepare field values strip schema definitions
        foreach ($schema as $field) {
            $this->{$field} = $parser->getNotationValue($this->{$field});
        }
    }

    /**
     * Fill value inside model fields.
     *
     * @param type       $values
     * @param null|mixed $map
     * @param null|mixed $prefix
     */
    public function fillSchemaFields($values, $map = null, $prefix = null)
    {
        //
        if (is_array($map)) {
            foreach ($map as $alias => $field) {
                if (isset($values[$alias])) {
                    $values[$field] = $values[$alias];
                }
            }
        }

        //
        foreach (static::getSchema() as $field => $aspects) {
            if (isset($aspects['Class']) && $aspects['Relation'] == '1:1') {
                $class = $aspects['Class'];

                $this->{$field} = $class::create(
                    $values,
                    $map,
                    $prefix.$field.'__'
                );
            }

            //
            $field = $prefix.$field;

            //
            if (isset($values[$field])) {
                $this->{$field} = $values[$field];
            }
        }

        //
        $key = $this->getPrimaryKey();

        //
        $field = $prefix.$key;

        //
        if ($key) {
            $this->{$key} = isset($values[$field])
                ? (int) $values[$field]
                : (int) $this->{$key};
        }
    }

    /**
     * Retrieve primary key field name.
     *
     * @return string
     */
    public static function getPrimaryKey()
    {
        $attribute = 'primary-key';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {
            $key = null;
            $schema = static::getSchema();

            foreach ($schema as $field => $aspects) {
                if ($aspects['Key'] == 'PRI') {
                    $key = $field;
                    break;
                }
            }

            static::setClassAttribute($attribute, $key);
        }

        return static::getClassAttribute($attribute);
    }

    /**
     * Get primary key value.
     */
    public function getPrimaryKeyValue()
    {
        //
        $key = static::getPrimaryKey();

        //
        return $key
            && isset($this->{$key})
            ? $this->{$key}
            : null;
    }

    /**
     * Retrieve primary key field name.
     *
     * @return string
     */
    public static function getMainField()
    {
        //
        $attribute = 'MainField';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {
            $mainField = null;

            //
            $schema = static::getSchema();

            //
            foreach ($schema as $field => &$attributes) {
                if ($attributes['Key'] == 'PRI') {
                    continue;
                }

                //
                $mainField = $field;

                //
                break;
            }

            // store as setting for future request
            static::setClassAttribute($attribute, $mainField);
        }

        // return primary key field name
        return static::getClassAttribute($attribute);
    }

    /**
     * @return mixed
     */
    public function getMainFieldValue()
    {
        //
        $mainField = static::getMainField();

        //
        return $mainField
            && isset($this->{$mainField})
            ? $this->{$mainField}
            : null;
    }

    /**
     * Get PrimaryKey name or MainField name.
     */
    protected static function getPrimaryKeyOrMainField()
    {
        //
        $key = static::getPrimaryKey();

        //
        return $key ? $key : static::getMainField();
    }

    /**
     * Get list of Class-Model fields use to store or read data.
     */
    public static function getModelFields()
    {
        return static::getSchemaFields();
    }

    /**
     * Retrieve primary key field name.
     *
     * @return array
     */
    protected static function getDefaultFields()
    {
        //
        $attribute = 'DefaultFields';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {
            $fields = [];

            //
            $schema = static::getSchema();

            //
            foreach ($schema as $field => $aspects) {
                if (isset($aspects['Class']) && $aspects['Relation'] == '1:1') {
                    $class = $aspects['Class'];
                    $fields[$field] = call_user_func($class.'::join', $field);
                } else {
                    $fields[] = $field;
                }
            }

            // store as setting for future request
            static::setClassAttribute($attribute, $fields);
        }

        // return primary key field name
        return static::getClassAttribute($attribute);
    }

    /**
     * @param mixed $field
     */
    public static function getFieldValues($field)
    {
        //
        $schema = static::getSchema();

        //
        return isset($schema[$field]['Enum'])
            ? $schema[$field]['Enum']
            : null;
    }

    /**
     * Get all static fields.
     */
    protected static function getStaticFields()
    {
        //
        $attribute = 'StaticFields';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {
            $class = static::getClass();

            //
            $reflection = new \ReflectionClass($class);

            //
            $fields = array_keys($reflection->getStaticProperties());

            // store as setting for future request
            static::setClassAttribute($attribute, $fields);
        }

        // return primary key field name
        return static::getClassAttribute($attribute);
    }

    /**
     *
     */
    protected static function getAllFieldsValues()
    {
        $attribute = 'fields-values';

        if (!static::hasClassAttribute($attribute)) {
            $class = static::getClass();
            $fields = get_class_vars($class);

            static::setClassAttribute($attribute, $fields);
        }

        return static::getClassAttribute($attribute);
    }

    /**
     * Get all fields of called class.
     */
    protected static function getAllFields()
    {
        return array_keys(get_class_vars(static::getClass()));
    }
}
