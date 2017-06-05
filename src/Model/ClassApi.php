<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

trait ClassApi
{
    /**
     * Global setting class attributes
     *
     * @var type
     */
    protected static $__global = [
        'schema-excluded-fields' => [
            '__global',
            '__attrib',
            '__config',
            'class',
            'table',
            'model',
        ],
    ];

    /**
     * Per-class attributes used as cache
     *
     * @var type
     */
    protected static $__attrib = [];

    /**
     * Retrieve static class complete name
     * with namespace prepended
     *
     * @return type
     */
    public static function getClass()
    {
        return isset(static::$class)
            ? static::$class
            : static::getCalledClass();
    }

    /**
     *
     * 
     * @return type
     */
    protected static function getCalledClass()
    {
        return trim(get_called_class(), '\\');
    }

    /**
     * Retrieve static class name
     *
     * @return type
     */
    protected static function getClassName()
    {
        $attribute = 'class-name';

        if (!static::hasClassAttribute($attribute)) {
            $class = static::getClass();
            $point = strrpos($class, '\\');
            $className = $point === false ? $class : substr($class, $point + 1);

            static::setClassAttribute($attribute, $className);
        } 
        
        return static::getClassAttribute($attribute);
    }

    /**
     *
     * 
     */
    protected static function hasClassAttribute($attribute)
    {
        $class = static::getClass();

        return isset(static::$__attrib[$class][$attribute]);
    }

    /**
     * 
     *
     */
    protected static function getClassAttribute($attribute)
    {
        $class = static::getClass();
      
        return static::$__attrib[$class][$attribute];
    }

    /**
     *
     * 
     */
    protected static function setClassAttribute($attribute, $value)
    {
        $class = static::getClass();

        static::$__attrib[$class][$attribute] = $value;
    }

    /**
     *
     *
     */
    protected static function delClassAttribute($attribute)
    {
        $class = static::getClass();

        unset(static::$__attrib[$class][$attribute]);
    }

    /**
     *
     *
     */
    protected static function getClassGlobal($attribute)
    {
        return static::$__global[$attribute];
    }

    /**
     *
     *
     */
    protected static function hasClassGlobal($attribute)
    {
        return isset(static::$__global[$attribute]);
    }

    /**
     * Check if called class have configuration value.
     *
     */
    protected static function hasClassConfig($key)
    {
        $config = static::getClassConfigInherit();

        return isset($config[$key]);
    }

    /**
     * Get configuration value of called class
     *
     */
    public static function getClassConfig($key)
    {
        $config = static::getClassConfigInherit();

        return $config[$key];
    }

    /**
     * Set configuration value of called class.
     *
     */
    protected static function setClassConfig($key, $value)
    {
        $config = static::getClassConfigInherit();

        $config[$key] = $value;
    }

    /**
     *
     *
     */
    protected static function getClassConfigArray()
    {
        return (array) static::$__config;
    }

    /**
     *
     */
    public static function getClassConfigInherit()
    {
        $attribute = 'class-config-inherit';

        if (!static::hasClassAttribute($attribute)) {
            $class = static::getClass();
            $stack = [];
            $inherit = [];

            while ($class) {
                $stack[] = $class::getClassConfigArray();
                $class = get_parent_class($class);
            }

            $stack = array_reverse($stack);

            foreach ($stack as $config) {
                $inherit = array_replace_recursive($inherit, $config);
            }

            static::setClassAttribute($attribute, $inherit);
        }

        return static::getClassAttribute($attribute);
    }

    /**
     *
     *
     */
    public static function resetClass()
    {
        $class = static::getClass();

        unset(static::$__attrib[$class]);
    }

    /**
     *
     *
     */
    public static function resetAllClass()
    {
        static::$__attrib = null;
    }

    /**
     *
     * @param type $prefix
     * @return type
     */
    protected static function getClassMethodsByPrefix($prefix = null)
    {
        //
        $attribute = 'MethodsByPrefix:'.$prefix;

        if (!static::hasClassAttribute($attribute)) {
            $class = static::getClass();
            $allMethods = get_class_methods($class);
            $methods = array();

            if (count($allMethods) > 0) {
                foreach ($allMethods as $method) {
                    if (preg_match('/^' . $prefix . '/i', $method)) {
                        $methods[] = $method;
                    }
                }
            }
            asort($methods);

            static::setClassAttribute($attribute, $methods);
        }

        //
        return static::getClassAttribute($attribute);
    }
}
