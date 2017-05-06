<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

use Javanile\Moldable\Database;

trait DatabaseApi 
{
    /**
     * Retriece linked database or default
     *
     * @return type
     */
    public static function getDatabase()
    {
        $attribute = 'database';

        if (!static::hasClassAttribute($attribute)) {
            $database = Database::getDefault();

            if (!$database) {
                static::error("Database not found", debug_backtrace(), 1);
            }

            static::setClassAttribute($attribute, $database);
        }

        return static::getClassAttribute($attribute);
    }

    /**
     * Link specific database to this table
     *
     * @return type
     */
    public static function setDatabase($database)
    {
        //
        $attribute = 'database';

        //
        static::setClassAttribute($attribute, $database);
    }
}
