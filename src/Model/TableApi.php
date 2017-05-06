<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

trait TableApi
{
    /**
     * Retrieve table name
     *
     * @return string
     */
    public static function getTable()
    {        
        $attribute = 'table';

        if (!static::hasClassAttribute($attribute)) {
            $name  = isset(static::$table) ? static::$table : static::getClassName();
            $table = static::getDatabase()->getPrefix($name);

            static::setClassAttribute($attribute, $table);
        }

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
}
