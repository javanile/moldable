
	##
	public static function getMethodsByPrefix($prefix=null) {
	
		##
		if (static::hasConfig($prefix)) {
			return static::getConfig($prefix);
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
	public static function join($fieldFrom, $fieldTo = null) {
		
		##
		if (!is_string($fieldFrom)) {
			trigger_error('Required field to join', E_USER_ERROR);
		}
		
		##
		return array(
			'Table'		=> static::getTable(),			
			'Class'		=> static::getClass(),
			'FieldFrom'	=> $fieldFrom,		
			'FieldTo'	=> $fieldTo ? $fieldTo : static::getMainField(),
			'JoinKey'	=> static::getPrimaryKey(),
		);		
	} 

	
	 ## usefull mysql func
    public static function now()
    {
        ##
        return date('Y-m-d H:i:s');
    }