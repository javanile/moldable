<?php

/*
 * 
 * 
\*/
namespace Javanile\SchemaDB\Model;

/**
 *
 */
use Javanile\SchemaDB\Notations;

/**
 *
 *
 *
 *
 */
trait ClassApi
{
	/**
	 * Bundle to collect info and stored cache
	 * 
	 * @var type 
	 */
    protected static $__Global__ = array(
		'SchemaExcludedFields' => array(
            '__Define__',
            '__Global__',
            '__Config__',
        ),		
	); 
	
	/**
	 *
	 * @var type 
	 */
	protected static $__Config__ = array(
		'SchemaExcludedFields' => array(),
	); 
			
	/**
	 * Retrieve static class name
	 * 
	 * @return type
	 */ 
    protected static function getClass()
    {
		//
		$attribute = 'Class';
		
        //
        return static::optDefine($attribute, static::getCalledClass());
    }

	/**
	 * Retrieve static class name
	 * 
	 * @return type
	 */ 
    protected static function getClassName()
    {
		//
		$class = static::getClass();
		
		//
		$point = strrpos($class, '\\');
		
		//
		return $point === false ? $class : substr($class, $point + 1);
    }

	/**
	 * 
	 * @return type
	 */
	protected static function getCalledClass() {

		//
		return get_called_class();		
	}

	/**
	 * 
	 */
	protected static function hasGlobal($attribute) {
				
		//
		return isset(static::$__Global__[$attribute]);		
	}

	/**
	 * 
	 */
	protected static function getGlobal($attribute) {
		
		//
		return static::$__Global__[$attribute];		
	}

	/**
	 * 
	 */
	protected static function setGlobal($attribute, $value) {
		
		//
		static::$__Global__[$attribute] = $value;		
	}

	/**
	 * 
	 * @param type $attribute
	 */
	protected static function delGlobal($attribute) {
		
		// clear cached
        unset(static::$__Global__[$attribute]);		
	}
	
	/**
	 * 
	 */
	protected static function hasConfig($attribute) {
			
		//
		$class = static::getClass();
			
		//
		return isset(static::$__Config__[$attribute][$class]);		
	}

	/**
	 * 
	 */
	protected static function getConfig($attribute) {
					
		//
		$class = static::getClass();
		
		//
		return static::$__Config__[$attribute][$class];		
	}

	/**
	 * 
	 */
	protected static function setConfig($attribute, $value) {
		
		//
		$class = static::getClass();
		
		//
		static::$__Config__[$attribute][$class] = $value;		
	}

	/**
	 * 
	 * @param type $attribute
	 */
	protected static function delConfig($attribute) {
		
		//
		$class = static::getClass();
		
		// clear cached
        unset(static::$__Config__[$attribute][$class]);		
	}
	
	/**
	 * 
	 */
	protected static function hasDefine($attribute) {
				
		//
		return isset(static::$__Define__[$attribute]);		
	}

	/**
	 * 
	 */
	protected static function getDefine($attribute) {
		
		//
		return static::$__Define__[$attribute];		
	}

	/**
	 * 
	 */
	protected static function setDefine($attribute, $value) {
		
		//
		static::$__Define__[$attribute] = $value;		
	}

	/**
	 * 
	 * @param type $attribute
	 */
	protected static function delDefine($attribute) {
		
		// clear cached
        unset(static::$__Define__[$attribute]);		
	}

	/**
	 * 
	 * @param type $attribute
	 */
	protected static function optDefine($attribute, $default=null) {
		
		//
		return isset(static::$__Define__) 
			&& isset(static::$__Define__[$attribute]) 
			 ? static::$__Define__[$attribute] 
			 : $default;
	}
	
	/**
	 * 
	 * @param type $prefix
	 * @return type
	 */
	protected static function getMethodsByPrefix($prefix=null) {
	
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

