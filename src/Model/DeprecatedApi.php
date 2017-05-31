<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

trait DeprecatedApi
{    
    /**
     * 
     * @param type $data
     * @return type
     */
    public static function build($data = null)
    {
        //
        return static::make($data);
    }
    
    /**
     *
     * @param type $data
     * @param type $map
     * @return type
     */
    public static function map($data, $map)
    {
        //
        $o = static::make($data);

        //
        foreach ($map as $m=>$f) {
            $o->{$f} = isset($data[$m]) ? $data[$m] : '';
        }

        //
        return $o;
    }
    
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

            //
            $compareWith = $filter.$field;

            //
            foreach($methods as $method) {

                //
                if (preg_match('/^'.$method.'/i',$compareWith)) {
                    $object->{$field} = call_user_func(array($object, $method), $value);
                }
            }
        }

        //
        return $object;
    }

    /**
     *
     * @param type $prefix
     * @return type
     */
    protected static function getMethodsByPrefix($prefix = null)
    {
        //
        $attribute = 'MethodsByPrefix:'.$prefix;

        //
        if (static::hasConfig($attribute)) {
            return static::getConfig($attribute);
        }

        //
        $class = static::getClass();

        //
        $allMethods = get_class_methods($class);

        //
        $methods = array();

        //
        if (count($allMethods) > 0) {
            foreach($allMethods as $method) {
                if (preg_match('/^'.$prefix.'/i',$method)) {
                    $methods[] = $method;
                }
            }
        }

        //
        asort($methods);

        //
        static::setConfig($attribute, $methods);

        //
        return $methods;
    }
}
