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
class Model extends Schema
{
	/**
	 * Bundle to collect info and stored cache
	 * 
	 * @var type 
	 */
    protected static $__ModelSettings__ = array(
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
	protected static $__ClassSettings__ = array(
		'schemaExcludedFields' => array(),
	); 
			
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
		return isset(static::$__ModelSettings__[$setting]);		
	}

	/**
	 * 
	 */
	public static function getModelSetting($setting) {
		
		##
		return static::$__ModelSettings__[$setting];		
	}

	/**
	 * 
	 */
	public static function setModelSetting($setting,$value) {
		
		##
		static::$__ModelSettings__[$setting] = $value;		
	}

	/**
	 * 
	 * @param type $setting
	 */
	public static function delModelSetting($setting) {
		
		## clear cached
        unset(static::$__ModelSettings__[$setting]);		
	}
	
	/**
	 * 
	 */
	public static function hasClassSetting($setting) {
			
		##
		return isset(static::$__ClassSettings__[$setting][static::getClass()]);		
	}

	/**
	 * 
	 */
	public static function getClassSetting($setting) {
					
		##
		return static::$__ClassSettings__[$setting][static::getClass()];		
	}

	/**
	 * 
	 */
	public static function setClassSetting($setting, $value) {
		
		##
		static::$__ClassSettings__[$setting][static::getClass()] = $value;		
	}

	/**
	 * 
	 * @param type $setting
	 */
	public static function delClassSetting($setting) {
		
		## clear cached
        unset(static::$__ClassSettings__[$setting][static::getClass()]);		
	}

	##
	public static function getMethodsByPrefix($prefix=null) {
	
		##
		if (static::hasClassSetting($prefix)) {
			return static::getClassSetting($prefix);
		}
		
		##
		$class = static::getClass();
		
		##
		$allMethods = get_class_methods($class);
		
		##
		$methods = array();
		
		##
		if (count($allMethods) > 0) {					
			foreach($allMethods as $method) {
				if (preg_match('/^'.$prefix.'/i',$method)) {
					$methods[] = $method;
				}
			}			
		} 
		
		##
		asort($methods);
		
		##
		static::setClassSetting($prefix, $methods);
		
		##
		return $methods;
	} 
		
    ##
    public static function connect($conn=null)
    {
		##
		static::updateTable();       
    }

	/**
	 * 
	 * @param type $values
	 * @param type $map
	 * @return \static
	 */
    public static function make($values=null, $map=null)
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
        foreach (static::getSchemaFields() as $field) {
            
			##
			if (isset($values[$field])) {
                $this->{$field} = $values[$field];
            }
        }

		##
        $key = $this->getPrimaryKey();

		##
        if ($key) {
            $this->{$key} = isset($values[$key]) ? (int) $values[$key] : (int) $this->{$key};
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
	
	/**
	 * 
	 * @param type $values
	 * @param type $filter
	 * @param type $map
	 * @return type
	 */
	public static function filter($values, $filter, $map=null) {
		
		##
		$object = is_array($values) ? static::make($values,$map) : $values;
	
		##
		$methods = static::getMethodsByPrefix($filter);
				
		##
		if (!is_object($object) || count($methods) == 0) { 
			return $object;			
		}
       	
		##
		foreach ($object as $field => $value) {
			
			##
			$compareWith = $filter.$field;
			
			##
			foreach($methods as $method) {				
				
				##
				if (preg_match('/^'.$method.'/i',$compareWith)) {
					$object->{$field} = call_user_func(array($object, $method), $value);					
				}				
			}
		}
        
		##
        return $object;		
	}
	
	/**
	 * 
	 */
	public static function join($field, $lookup=null) {
		
		##
		if (!is_string($field)) {
			trigger_error('Required field to join', E_USER_ERROR);
		}
		
		##
		return array(
			'alias' => get_called_class(),
			'class' => get_called_class(),
			'field' => $field,		
			'table' => static::getTable(),
			'key'	=> static::getPrimaryKey(),
			'lookup'=> $lookup ? $lookup : null,
		);		
	} 
}

