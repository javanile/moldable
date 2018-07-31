<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait FilterApi
{
    /**
     * @param type $values
     * @param type $filter
     * @param type $map
     *
     * @return type
     */
    public static function filter($values, $filter, $map = null)
    {
        //
        $object = is_array($values) ? static::create($values, $map) : $values;
        $methods = static::getClassMethodsByPrefix($filter);

        //
        if (!is_object($object) || count($methods) == 0) {
            return $object;
        }

        //
        foreach ($object as $field => $value) {
            $compareWith = $filter.$field;

            foreach ($methods as $method) {
                if (preg_match('/^'.$method.'/i', $compareWith)) {
                    $object->{$field} = call_user_func([$object, $method], $value);
                }
            }
        }

        return $object;
    }
}
