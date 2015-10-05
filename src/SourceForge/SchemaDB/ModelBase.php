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
        $index = (int) $id;

        ##
        $table = static::getTable();

        ## get primary key
        $key = static::getPrimaryKey();

		##
		$join = null;
		
        ## parse SQL select fields
        $selectFields = Mysql::selectFields($fields, $join);

        ## prepare SQL query
        $sql = "SELECT {$selectFields} FROM {$table} {$join} WHERE {$key}='{$index}' LIMIT 1";

        ## fetch data on database and return it
        return static::fetch($sql, false, is_string($fields));
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
		
        ## parse SQL select fields
        $selectFields = Mysql::selectFields($fields, $class, $join);

        ## prepare SQL query
        $sql = "SELECT {$selectFields} FROM {$table} {$join} WHERE {$field}='{$value}' LIMIT 1";

        ## fetch data on database and return it
        return static::fetch($sql, false, is_string($fields));
	}

}