<?php

/*
 * 
 * 
 * 
 * 
\*/
namespace Javanile\SchemaDB\Model;

/**
 *
 */
use Javanile\SchemaDB\Database;

/**
 * static part of sdbClass
 *
 *
 */
trait TableApi
{   	
    /**
	 * Retrieve table name
	 * 
	 * @return string
	 */
    public static function getTable()
    {        
		// config attribute that contain table name
		$attribute = 'Table';

		// retrieve value from class setting definition
		if (!static::hasClassAttribute($attribute)) {

            //
            $name = isset(static::$table)
                  ? static::$table
                  : static::getClassName();
            
            // get prefix
            $table = static::getDatabase()->getPrefix() . $name;

            // store as setting for future request
            static::setClassAttribute($attribute, $table);
        }

        // return complete table name
        return static::getClassAttribute($attribute);
    }
   	
    /**
	 * 
	 * @return type
	 */ 
    public static function applyTable()
    {
        //
		$attribute = 'ApplyTableExecuted';

		// avoid re-update by check the cache
        if (!static::hasClassAttribute($attribute)) {

            //
            $database = static::getDatabase();
            
            // if model is not connectect to any db return
            if (!$database) {
                throw new \Javanile\SchemaDB\Exception("No database");
            }

            // get table name
            $table = static::getTable();

            //
            $schema = static::getSchema();

            //
            if (!$schema) {
                throw new \Javanile\SchemaDB\Exception("Empty model class");
            }

            // have a valid schema update db table
            static::getDatabase()->applyTable($table, $schema, false);

            // cache last update avoid multiple call
            static::setClassAttribute($attribute, time());
        }
    }
	
	/**
	 * Retriece linked database or default
	 * 
	 * @return type
	 */
    protected static function getDatabase()
    {		
		//
		$attribute = 'Database';

        //
        if (!static::hasClassAttribute($attribute)) {

            //
            $database = Database::getDefault();

            //
            // TODO: check if no have database connected $database == null
            if (!$database) {
                die('no default database!!!!');
                debug_print_backtrace();
            }

            //
            static::setClassAttribute($attribute, $database);
        }
       
        //
        return static::getClassAttribute($attribute);
    }

	/**
	 * Link specific database to this table
	 * 
	 * @return type
	 */
    protected static function setDatabase($database)
    {        
		//
		$attribute = 'Database';
		
		//
		static::setConfig($attribute, $database);
    }

    /**
     *
     *
     * @param type $array
     */
    protected static function fetch(
        $sql,
        $values=null,
        $array=false,
        $value=false,
        $cast=true
    ) {
		// requested a single record
		if (!$array && !$value && $cast) {

            //
            $record = static::getDatabase()->getRow($sql, $values);

            //
            return $record ? static::make($record): null;
		}

        // requested a single record
		else if ($array && !$value && $cast) {

            //
            $records = static::getDatabase()->getResults($sql, $values);

            
            //
            if (!$records) {
                return;
            }

            //
            foreach($records as &$record) {
                $record = static::make($record);
            }

            //
            return $records;
		}


    }
	
	
}
