<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

trait FieldApi
{
	/**
     * Retrieve primary key field name
     *
     * @return boolean
     */
    public static function getPrimaryKey()
    {
        //
        $attribute = 'PrimaryKey';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {

            //
            $key = false;

            //
            $schema = static::getSchema();

            //
            foreach ($schema as $field => &$attributes) {

                //
                if ($attributes['Key'] == 'PRI') {

                    //
                    $key = $field;

                    //
                    break;
                }
            }

            // store as setting for future request
            static::setClassAttribute($attribute, $key);
        }

        // return primary key field name
        return static::getClassAttribute($attribute);
    }

    /**
     * 
     * 
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
     * Retrieve primary key field name
     *
     * @return boolean
     */
    public static function getMainField()
    {
        //
        $attribute = 'MainField';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {

            //
            $mainField = false;

            //
            $schema = static::getSchema();

            //
            foreach ($schema as $field => &$attributes) {

                //
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
     *
     *
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
     *
     *
     */
    protected static function getPrimaryKeyOrMainField()
    {
        //
        $key = static::getPrimaryKey();

        //
        return $key ? $key : static::getMainField(); 
    }

    /**
     * Retrieve primary key field name
     *
     * @return boolean
     */
    protected static function getDefaultFields()
    {
        //
        $attribute = 'DefaultFields';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {

            //
            $fields = array();

            //
            $schema = static::getSchema();

            //
            foreach($schema as $field => $aspects) {
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
     *
     *
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
     *
     *
     * @param type $values
     */
    public function fill($values, $map=null, $prefix=null)
    {
        //
        foreach (static::getSchema() as $field => $aspects) {

            //
            if (isset($aspects['Class']) && $aspects['Relation'] == '1:1') {

                //
                $class = $aspects['Class'];

                //
                $this->{$field} = $class::make(
                    $values,
                    $map,
                    $prefix . $field . '__'
                );
            }

            //
            $field = $prefix . $field;

            //           
            if (isset($values[$field])) {
                $this->{$field} = $values[$field];
            }
        }

        //
        $key = $this->getPrimaryKey();

        //
        $field = $prefix . $key;

        //
        if ($key) {
            $this->{$key} = isset($values[$field])
                          ? (int) $values[$field]
                          : (int) $this->{$key};
        }
    }
}