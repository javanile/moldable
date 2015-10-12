<?php

/*\
 * 
 * 
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 * static part of sdbClass
 *
 *
 */
class ModelFields extends ModelSchema 
{
	/**
	 * Retrieve primary key field name
	 *  
	 * @return boolean
	 */
    protected static function getPrimaryKey()
    {
		##
		$attribute = 'PrimaryKey';
		
		## retrieve value from class setting definition
		if (static::hasConfig($attribute)) {
			return static::getConfig($attribute);
		}
		
		##
		$key = false;
		
		##
		$schema = static::getSchema();

        ##
        foreach ($schema as $field => &$attributes) {

            ##
            if ($attributes['Key'] == 'PRI') {
	
				##
				$key = $field;
                
				##
				break;
            }
        }
			
		## store as setting for future request
		static::setConfig($attribute, $key);
								
        ## return primary key field name
        return $key;
    }
	
	/**
	 * Retrieve primary key field name
	 *  
	 * @return boolean
	 */
    protected static function getMainField()
    {
		##
		$attribute = 'MainField';
		
		## retrieve value from class setting definition
		if (static::hasConfig($attribute)) {
			return static::getConfig($attribute);
		}
			
		##
		$mainField = false;
		
		##
		$schema = static::getSchema();

        ##
        foreach ($schema as $field => &$attributes) {

            ##
            if ($attributes['Key'] == 'PRI') {
				continue;	
            }
			
			##
			$mainField = $field;
			
			##
			break;
        }
        		
		## store as setting for future request
		static::setConfig($attribute, $mainField);
								
        ## return primary key field name
        return $mainField;
    }
	
	/**
	 * Retrieve primary key field name
	 *  
	 * @return boolean
	 */
    protected static function getDefaultFields()
    {
		##
		$attribute = 'DefaultFields';
		
		## retrieve value from class setting definition
		if (static::hasConfig($attribute)) {
			return static::getConfig($attribute);
		}
		
		##
		$fields = array();
        
		##
		$schema = static::getSchema();
		
		##
		foreach($schema as $field => $attributes) {
			if (isset($attributes['Class'])) {
				$class = $attributes['Class'];
				$fields[$field] = call_user_func($class.'::join', $field);
			} else {
				$fields[] = $field;
			}			
		}
						
		## store as setting for future request
		static::setConfig($attribute, $fields);
								
        ## return primary key field name
        return $fields;
    }
	
}