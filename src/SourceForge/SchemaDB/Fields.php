<?php

/*\
 * 
 * 
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * static part of sdbClass
 *
 *
 */
class Fields 
{
    /**
	 * schemadb mysql constants for rapid fields creation
	 */
    const PRIMARY_KEY			= '<<#primary_key>>';
    const PRIMARY_KEY_INT_20	= '<<#primary_key>>';
    
	/**
	 * 
	 */
	const VARCHAR		= '<<{"Type":"varchar(255)"}>>';
    const VARCHAR_80	= '<<{"Type":"varchar(80)"}>>';
    const VARCHAR_255	= '<<{"Type":"varchar(255)"}>>';
    const TEXT			= '<<{"Type":"text"}>>';
    
	/**
	 * 
	 * 
	 */
	const INT					= '<<{"Type":"int(10)"}>>';
    const INT_10			= '<<{"Type":"int(10)"}>>';
    const INT_14			= '<<{"Type":"int(14)"}>>';
    
	/**
	 * 
	 */
	const FLOAT				= '<<{"Type":"float(14,4)"}>>';
    const FLOAT_14_4		= '<<{"Type":"float(14,4)"}>>';
    
	/**
	 * 
	 */
	const TIME				= '00:00:00';
    const DATE				= '0000-00-00';
    const DATETIME			= '0000-00-00 00:00:00';
	
	/**
	 * Retrieve primary key field name
	 *  
	 * @return boolean
	 */
    public static function getPrimaryKey()
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
    public static function getMainField()
    {
		##
		$setting = 'MainField';
		
		## retrieve value from class setting definition
		if (static::hasClassSetting($setting)) {
			return static::getClassSetting($setting);
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
		static::setClassSetting($setting, $mainField);
								
        ## return primary key field name
        return $mainField;
    }
	
	/**
	 * Retrieve primary key field name
	 *  
	 * @return boolean
	 */
    public static function getDefaultFields()
    {
		##
		$setting = 'DefaultFields';
		
		## retrieve value from class setting definition
		if (static::hasClassSetting($setting)) {
			return static::getClassSetting($setting);
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
		static::setClassSetting($setting, $fields);
								
        ## return primary key field name
        return $fields;
    }
	
}