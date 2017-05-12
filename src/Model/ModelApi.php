<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

trait ModelApi
{
	/**
     * Retrieve static class name
     *
     * @return type
     */
    protected static function getModel()
    {
        $attribute = 'model';

        if (!static::hasClassAttribute($attribute)) {
            $class = static::getClass();
            $point = strrpos($class, '\\');
            $className = $point === false ? $class : substr($class, $point + 1);

            static::setClassAttribute($attribute, $className);
        } 
        
        return static::getClassAttribute($attribute);
    }
}
