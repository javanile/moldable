<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * 
 * 
 */
class ModelBase extends Model {

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
        $sql = "SELECT {$selectFields} FROM {$table} AS {$alias} {$join} WHERE {$alias}.{$key}='{$index}' LIMIT 1";

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

}