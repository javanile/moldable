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
class Model extends Table
{
	/**
	 * Bundle to collect info and stored cache
	 * 
	 * @var type 
	 */
    protected static $__ModelSettings = array(
		'exclude' => array(
            'class',
            'table',
            '__ModelSettings',
            '__ClassSettings',
        ),		
	); 
	
	/**
	 *
	 * @var type 
	 */
	protected static $__ClassSettings; 
			
	/**
	 * Retrieve static class name
	 * 
	 * @return type
	 */ 
    public static function getClass()
    {
        ##
        return isset(static::$class) ? static::$class : get_called_class();
    }
	
	/**
	 * 
	 */
	public static function hasModelSetting($setting) {
				
		##
		return isset(static::$__ModelSettings[$setting]);		
	}

	/**
	 * 
	 */
	public static function getModelSetting($setting) {
		
		##
		return static::$__ModelSettings[$setting];		
	}

	/**
	 * 
	 */
	public static function setModelSetting($setting,$value) {
		
		##
		static::$__ModelSettings[$setting] = $value;		
	}

	/**
	 * 
	 * @param type $setting
	 */
	public static function delModelSetting($setting) {
		
		## clear cached
        unset(static::$__ModelSettings[$setting]);		
	}
	
	/**
	 * 
	 */
	public static function hasClassSetting($setting) {
			
		##
		return isset(static::$__ClassSettings[$setting][static::getClass()]);		
	}

	/**
	 * 
	 */
	public static function getClassSetting($setting) {
		
		##
		return static::$__ClassSettings[$setting][static::getClass()];		
	}

	/**
	 * 
	 */
	public static function setClassSetting($setting,$value) {
		
		##
		static::$__ClassSettings[$setting][static::getClass()] = $value;		
	}

	/**
	 * 
	 * @param type $setting
	 */
	public static function delClassSetting($setting) {
		
		## clear cached
        unset(static::$__ClassSettings[$setting][static::getClass()]);		
	}

	##
    public static function make($data=null)
    {
        ##
        $o = new static();

        ##
        if ($data) {
            $o->fill($data);
        }

        ##
        return $o;
    }

    ##
    public static function build($data=null)
    {
        ##
        return static::make($data);
    }
	
	##
    public static function map($data,$map)
    {
        ##
        $o = static::make($data);

        ##
        foreach ($map as $m=>$f) {
            $o->{$f} = isset($data[$m]) ? $data[$m] : '';
        }

        ##
        return $o;
    }
}

