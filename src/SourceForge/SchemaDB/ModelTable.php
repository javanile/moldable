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
class ModelTable extends Fields
{   	
    /**
	 * Retrieve table name
	 * 
	 * @return string
	 */
    public static function getTable()
    {        
		## config attribute that contain table name
		$attribute = 'Table';

		## retrieve value from class setting definition
		if (static::hasConfig($attribute)) {
			return static::getConfig($attribute);
		}
		
		## 
		else if (isset(static::$table)) {
            $name = static::$table;
        } 
		
		##
		elseif (isset(static::$class)) {
            $name = static::$class;
        } 
		
		##
		else {
            $name = get_called_class();
        }

		## get prefix
        $table = static::getDatabase()->getPrefix() . $name;

		## store as setting for future request
		static::setClassSetting($attribute, $table);
								
        ## return complete table name
        return $table;
    }
   	
    /**
	 * 
	 * @return type
	 */ 
    public static function updateTable()
    {        
		## if model is not connectect to any db return
		if (!static::getDatabase()) {
			return;			
		}	

		## avoid re-update by check the cache
        if (static::hasClassSetting('update')) {	
			return;
		}

        ## get table name
        $table = static::getTable();

		## 
		$schema = static::getSchema();
						
        ## have a valid schema update db table
        if ($schema) {
            static::getDatabase()->updateTable($table, $schema, false);
        }

        ## cache last update avoid multiple call
        static::setClassSetting('update', time());
    }
	
	/**
	 * Retriece linked database or default
	 * 
	 * @return type
	 */
    protected static function getDatabase()
    {		
        ##
        return static::hasClassSetting('database') ? static::getClassSetting('database') : Database::getDefault();
    }

	/**
	 * Link specific database to this table
	 * 
	 * @return type
	 */
    protected static function setDatabase($database)
    {        
		##
		static::setConfig('database', $database);
    }

    /**
     *
     *
     * @param type $array
     */
    protected static function fetch($sql, $array=false, $value=false, $cast=true)
    {	
		##
		if ($array) {
			$result = static::getDatabase()->getResults($sql);			
		}
		
        ##
        else if (!$value) {
		
			##
			$row = static::getDatabase()->getRow($sql);
			
			##
			return $cast ? static::make($row) : (object) $row; 
        }

        ##
        else {
            $result = static::getDatabase()->getValue($sql);
        }
			
		return $result;
    }
	
	
}
