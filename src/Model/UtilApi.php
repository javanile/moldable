<?php
/**
 *
 *
 */

namespace Javanile\Moldable\Model;

trait UtilApi
{    
    /**
     *
     * @return type
     */
    public static function now()
    {
        //
        return date('Y-m-d H:i:s');
    }

    /**
     *
     *
     * @param type $values
     * @param type $filter
     * @param type $map
     * @return type
     */
    protected static function filter($values, $filter, $map = null)
    {
        //
        $object = is_array($values) ? static::make($values, $map) : $values;

        //
        $methods = static::getMethodsByPrefix($filter);

        //
        if (!is_object($object) || count($methods) == 0) {
            return $object;
        }

        //
        foreach ($object as $field => $value) {
            $compareWith = $filter.$field;

            //
            foreach($methods as $method) {
                if (preg_match('/^'.$method.'/i',$compareWith)) {
                    $object->{$field} = call_user_func(array($object, $method), $value);
                }
            }
        }

        return $object;
    }
}
