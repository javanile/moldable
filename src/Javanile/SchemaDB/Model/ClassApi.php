<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

trait ClassApi
{
	/**
	 * Global setting class attributes
	 * 
	 * @var type 
	 */
    protected static $__global__ = [
		'SchemaExcludedFields' => [
            '__global__',
            '__attributes__',
            '__config__',
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
    protected static $__attributes__ = [];

	/**
	 *
	 * @var type 
	 */
	public static $__config__ = [];
    		
	/**
	 * Retrieve static class complete name
     * with namespace prepended
	 * 
	 * @return type
	 */ 
    protected static function getClass()
    {
        //
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
		//
		return trim(get_called_class(),'\\');
	}

	/**
	 * Retrieve static class name
	 * 
	 * @return type
	 */ 
    protected static function getClassName()
    {		
        //
        $attribute = 'ClassName';

        //
        if (!static::hasClassAttribute($attribute)) {

            //
            $class = static::getClass();

            //
            $point = strrpos($class, '\\');

            //
            $className = $point === false ? $class : substr($class, $point + 1);
            
            //
            static::setClassAttribute($attribute, $className);
        } 
        
        //
        return static::getClassAttribute($attribute);
    }
	
	/**
	 *
     * 
	 */
	protected static function hasClassAttribute($attribute)
    {
		//
		$class = static::getClass();
			
		//
		return isset(static::$__attributes__[$class][$attribute]);
	}

	/**
     * 
	 * 
	 */
	protected static function getClassAttribute($attribute)
    {
		//
		$class = static::getClass();
      
		//
		return static::$__attributes__[$class][$attribute];
	}

	/**
	 *
     * 
	 */
	protected static function setClassAttribute($attribute, $value)
    {
		//
		$class = static::getClass();
		
		//
		static::$__attributes__[$class][$attribute] = $value;
	}

    /**
	 *
     *
	 */
	protected static function delClassAttribute($attribute)
    {
		//
		$class = static::getClass();

		//
		unset(static::$__attributes__[$class][$attribute]);
	}

    /**
     *
     *
     */
    protected static function getClassGlobal($attribute)
    {
        //
        return static::$__global__[$attribute];
    }

    /**
     *
     *
     */
    protected static function hasClassGlobal($attribute)
    {
        //
        return isset(static::$__global__[$attribute]);
    }

    /**
     *
     *
     */
    protected static function getClassConfig($attribute)
    {
        //
        return static::$__config__[$attribute];
    }

    /**
     *
     *
     */
    protected static function hasClassConfig($attribute)
    {
        //
        return isset(static::$__config__[$attribute]);
    }
}

