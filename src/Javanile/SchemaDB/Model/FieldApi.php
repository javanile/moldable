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
    protected static function getPrimaryKey()
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
	 * Retrieve primary key field name
	 *  
	 * @return boolean
	 */
    protected static function getMainField()
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
            foreach($schema as $field => $attributes) {
                if (isset($attributes['Class'])) {
                    $class = $attributes['Class'];
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
        $fields = get_class_vars(static::getClass());

        //
        return isset($fields[$field])
            && is_array($fields[$field])
             ? $fields[$field]
             : null;
    }

    /**
	 *
	 *
	 * @param type $values
	 */
    public function fill($values)
    {
		//
        foreach (static::getSchemaFields() as $field) {

			//
			if (isset($values[$field])) {
                $this->{$field} = $values[$field];
            }
        }

		//
        $key = $this->getPrimaryKey();

		//
        if ($key) {
            $this->{$key} = isset($values[$key])
                          ? (int) $values[$key]
                          : (int) $this->{$key};
        }
    }
}