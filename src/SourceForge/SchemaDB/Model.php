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
    public static function getDatabase()
    {
		var_Dump("Ciao",Database::getDefault());
		echo '<pre>';
		debug_print_backtrace();
		echo '</pre>';
        ##
        return static::hasClassSetting('database') ? static::getClassSetting('database') : Database::getDefault();
    }

	##
    public static function setDatabase($database)
    {        
		##
		static::setClassSetting('database',$database);
    }

    ##
    public static function connect($conn=null)
    {
		##
		static::updateTable();       
    }

	##
    public static function make($values=null)
    {
        ##
        $object = new static();

        ##
        if ($values) {
            $object->fill($values);
        }

        ##
        return $object;
    }

    ##
    public static function build($data=null)
    {
        ##
        return static::make($data);
    }
	
	/**
	 * 
	 * 
	 * @param type $values
	 */
    public function fill($values)
    {
		##
        foreach ($this->getFields() as $f) {
            
			##
			if (isset($values[$f])) {
                $this->{$f} = $values[$f];
            }
        }

		##
        $k = $this->getPrimaryKey();

		##
        if ($k) {
            $this->{$k} = isset($values[$k]) ? (int) $values[$k] : (int) $this->{$k};
        }
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

