<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

trait ModelApi
{
    /**
     * Retrieve model name.
     *
     * @return string
     */
    public static function getModel()
    {
        $attribute = 'model';

        if (!static::hasClassAttribute($attribute)) {
            $class = static::getClass();
            $slash = strrpos($class, '\\');
            $model = $slash === false ? $class : substr($class, $slash + 1);

            static::setClassAttribute($attribute, $model);
        }

        return static::getClassAttribute($attribute);
    }
}
