<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Exception;

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
     *
     */
    protected static function isAdamantTable()
    {
        // config attribute that contain model table adamant 
        $attribute = 'Adamant';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {

            //
            $adamant = isset(static::$__adamant__)
                     ? static::$__adamant__
                     : isset(static::$adamant)
                     ? static::$adamant
                     : isset(static::$__adamant)
                     ? static::$__adamant
                     : true;

            // store as setting for future request
            static::setClassAttribute($attribute, $adamant);
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
        if (static::isAdamantTable()) {
            return;
        }

        //
        $attribute = 'ApplyTableExecuted';

        // avoid re-update by check the cache
        if (!static::hasClassAttribute($attribute)) {

            // retrieve database
            $database = static::getDatabase();
            
            // if model is not connectect to any db return
            if (!$database) {
                static::error('Database not found', debug_backtrace(), 2);
            }
            
            // retrieve class model schema
            $schema = static::getSchema();
           
            //
            if (!$schema) {
 
                //
                $reflector = new \ReflectionClass(static::getClass());
                
                //
                static::error('Model class without attributes', [[
                    'file' => $reflector->getFileName(),
                    'line' => $reflector->getStartLine(),
                ]]);
            }

            // get table name
            $table = static::getTable();

            // have a valid schema update db table
            $database->applyTable($table, $schema, false);

            // cache last update avoid multiple call
            static::setClassAttribute($attribute, time());
        }
    }        
}
