<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 *
 *
 *
 *
 */
class ConfigurableClass
{
	/**
	 * Bundle to collect info and stored cache
	 * 
	 * @var type 
	 */
    protected static $__Global__ = array(
		'schemaExcludedFields' => array(
            'class',
            'table',
            '__ModelSettings__',
            '__ClassSettings__',
        ),		
	); 
	
	/**
	 *
	 * @var type 
	 */
	protected static $__Config__ = array(
		'schemaExcludedFields' => array(),
	); 
			
	/**
	 * Retrieve static class name
	 * 
	 * @return type
	 */ 
    protected static function getClass()
    {
        ##
        return isset(static::$class) ? static::$class : get_called_class();
    }
	
	/**
	 * 
	 */
	protected static function hasGlobal($attribute) {
				
		##
		return isset(static::$__Global__[$attribute]);		
	}

	/**
	 * 
	 */
	protected static function getGlobal($attribute) {
		
		##
		return static::$__Global__[$attribute];		
	}

	/**
	 * 
	 */
	protected static function setGlobal($attribute, $value) {
		
		##
		static::$__Global__[$attribute] = $value;		
	}

	/**
	 * 
	 * @param type $attribute
	 */
	protected static function delGlobal($attribute) {
		
		## clear cached
        unset(static::$__Global__[$attribute]);		
	}
	
	/**
	 * 
	 */
	protected static function hasConfig($attribute) {
			
		##
		$class = static::getClass();
		
		##
		return isset(static::$__Config__[$attribute][$class]);		
	}

	/**
	 * 
	 */
	protected static function getConfig($attribute) {
					
		##
		$class = static::getClass();
		
		##
		return static::$__Config__[$attribute][$class];		
	}

	/**
	 * 
	 */
	protected static function setConfig($attribute, $value) {
		
		##
		$class = static::getClass();
		
		##
		static::$__Config__[$attribute][$class] = $value;		
	}

	/**
	 * 
	 * @param type $attribute
	 */
	protected static function delConfig($attribute) {
		
		##
		$class = static::getClass();
		
		## clear cached
        unset(static::$__Config__[$attribute][$class]);		
	}
}

