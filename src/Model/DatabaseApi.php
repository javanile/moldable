<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Database;

trait DatabaseApi 
{
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
            if (!$database) {
                static::error("Database not found", debug_backtrace(), 1);
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
        static::setClassAttribute($attribute, $database);
    }
}