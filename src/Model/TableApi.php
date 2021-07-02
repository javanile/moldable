<?php
/**
 * Trait with utility methods to work on tables.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

trait TableApi
{
    /**
     * Retrieve table name.
     *
     * @return string
     */
    public static function getTable()
    {
        $attribute = 'table';

        if (!static::hasClassAttribute($attribute)) {
            $name = !isset(static::$table)
                ? static::getClassName()
                : static::$table;

            $conventionName = Functions::applyConventions(
                static::getClassConfig('table-name-conventions'),
                $name
            );

            $tableName = static::getDatabase()->getPrefix($conventionName);

            static::setClassAttribute($attribute, $tableName);
        }

        return static::getClassAttribute($attribute);
    }

    public static function isAdamantTable()
    {
        // config attribute that contain model table adamant
        $attribute = 'Adamant';

        // retrieve value from class setting definition
        if (!static::hasClassAttribute($attribute)) {
            $adamant = isset(static::$__adamant__)
                ? static::$__adamant__
                : (isset(static::$adamant)
                    ? static::$adamant
                    : (isset(static::$__adamant) ? static::$__adamant : false)
                );

            // store as setting for future request
            static::setClassAttribute($attribute, $adamant);
        }

        // return complete table name
        return static::getClassAttribute($attribute);
    }
}
