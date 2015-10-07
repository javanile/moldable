<?php

/*\
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 * 
 * 
 */
class ModelProtectedAPI extends ModelRecord {

	/**
	 * 
	 * @param type $index
	 * @param type $fields
	 * @return type
	 */
	public static function loadByPrimaryKey($index, $fields=null) {
						
        ##
        $table = static::getTable();

        ## get primary key
        $key = static::getPrimaryKey();
		
		##
		$class = static::getClass();

		##
		$alias = $class;
		
		##
		$join = null;
		
		##
		$allFields = $fields ? $fields : static::getDefaultFields(); 
		
        ## parse SQL select fields
        $selectFields = Mysql::selectFields($allFields, $class, $join);

        ## prepare SQL query
        $sql = " SELECT {$selectFields} "
			 . "   FROM {$table} AS {$alias} {$join} "
			 . "  WHERE {$alias}.{$key}='{$index}' "
			 . "  LIMIT 1";

        ## fetch data on database and return it
        return static::fetch($sql, false, is_string($fields), is_null($fields));
	}

	/**
	 * 
	 * @param type $index
	 * @param type $fields
	 * @return type
	 */
	public static function loadByMainField($value, $fields=null) {
			
        ##
        $table = static::getTable();

        ## get primary key
        $field = static::getMainField();

		##
		$class = static::getClass(); 
		
		##
		$join = null;
		
		##
		$allFields = $fields ? $fields : static::getDefaultFields(); 
		
        ## parse SQL select fields
        $selectFields = Mysql::selectFields($allFields, $class, $join);

        ## prepare SQL query
        $sql = "SELECT {$selectFields} FROM {$table} {$join} WHERE {$field}='{$value}' LIMIT 1";

        ## fetch data on database and return it
        return static::fetch($sql, false, is_string($fields));
	}
	
	/**
	 * 
	 * @param type $query
	 * @param type $fields
	 * @return type
	 */
	public static function loadByQuery($query, $fields=null) {
						
        ##
        $table = static::getTable();
		
		##
		$class = static::getClass();

		##
		$alias = $class;
		
		##
		$join = null;
		
		##
		$allFields = $fields ? $fields : static::getDefaultFields(); 
		
        ## parse SQL select fields
        $selectFields = MysqlComposer::selectFields($allFields, $class, $join);
		
		##
		$whereConditions = array();
		
		##
        if (isset($query['where'])) {
            $whereConditions[] = "(".$query['where'].")";
        }

        ##
        foreach ($query as $field => $value) {

            ##
            if (in_array($field, array('order','where'))) {
                continue;
            }

            ##
            $whereConditions[] = "{$field} = '{$value}'";
        }

        ##
        $where = implode(' AND ', $whereConditions);
		
        ## prepare SQL query
        $sql = " SELECT {$selectFields} "
			 . "   FROM {$table} AS {$alias} {$join} "
			 . "  WHERE {$where} "
			 . "  LIMIT 1";

        ## fetch data on database and return it
        return static::fetch($sql, false, is_string($fields), is_null($fields));
	}
	
	/**
	 * 
	 * @param type $values
	 * @param type $filter
	 * @param type $map
	 * @return type
	 */
	protected static function filter($values, $filter, $map=null) {
		
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
	
	


}