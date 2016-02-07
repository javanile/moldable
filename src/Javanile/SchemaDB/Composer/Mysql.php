<?php

/*
 * 
 * 
\*/
namespace Javanile\SchemaDB\Composer;

/**
 * A collection of MySQL stataments builder
 * used with mysql query template and place-holder replacing
 */
class Mysql
{
    //
    private static $defaults = array(
        'Attributes' => array(
            'Type' => 'int(11)',
        ),
    );

    //
    public static function columnDefinition($attributes, $order=true)
    {        
		//
		$a = &$attributes;

        //
		$Key = isset($a['Key']) && $a['Key'] == 'PRI' ? 'PRIMARY KEY' : '';

        //
        $Type = isset($a['Type']) ? strtolower($a['Type']) : static::$defaults['Attributes']['Type'];
        
		//
		$Null = isset($a['Null']) && ($a['Null'] == 'NO' || !$a['Null']) ? 'NOT NULL' : 'NULL';

		//
		$Extra = isset($a['Extra']) ? $a['Extra'] : '';

        //
        if (!isset($a['Default']) 
            || $a['Default'] === 'NO'
            || $a['Default'] === ''
            || $a['Key']) {
            $Default = '';            
        } 
        
        //
        else if ($a['Default'] === 'CURRENT_TIMESTAMP') {
            $Default = 'DEFAULT CURRENT_TIMESTAMP';                        
        } 
        
        //
        else {            
            $Default = 'DEFAULT '."'".$a['Default']."'";
        }

        //
		$sql = $Type.' '.$Null.' '.$Default.' '.$Key.' '.$Extra;

        //
        if ($order) {
            $First = isset($a['First']) && $a['First'] ? 'FIRST' : '';
            $Before = isset($a['Before']) && $a['Before'] ? 'AFTER '.$a['Before'] : '';
			$sql .= ' '.$First.' '.$Before;
        }

        //
        return trim($sql);
    }

    /**
     * Prepare sql code to create a table
     *
     * @param  string $table The name of table to create
     * @param  array  $schema Skema of the table contain column definitions
     * @return string Sql code statament of CREATE TABLE
     */
    public static function createTable($table, $schema)
    {		
		//
        $columnsArray = array();

        // loop throut schema
        foreach ($schema as $field => $attributes) {

            //
            if (is_numeric($field) && is_string($attributes)) {

                //
                $field = $attributes;

                //
                $attributes = array();
            }

			//
			$column = static::columnDefinition($attributes, false);
			
            //
			$columnsArray[] = "`{$field}` {$column}";
        }

        // implode
        $columns = implode(',', $columnsArray);
		
        // template sql to create table
        $sql = "CREATE TABLE `{$table}` ({$columns})";

        // return the sql
        return $sql;
    }

    /**
	 * 
	 * @param type $table
	 * @param type $field
	 * @param type $attributes
	 * @return type
	 */
    public static function alterTableAdd($table, $field, $attributes)
    {
        //
        $column = static::columnDefinition($attributes);

        //
        $sql = "ALTER TABLE `{$table}` ADD COLUMN `{$field}` {$column}";

        //
        return $sql;
    }

    /**
	 * Retrieve sql to alter table definition
	 * 
	 * @param type $t
	 * @param type $f
	 * @param type $d
	 * @return type
	 */
    public static function alterTableChange($table, $field, $attributes)
    {
        //
        $column = static::columnDefinition($attributes);

        //
        $sql = "ALTER TABLE `{$table}` CHANGE COLUMN `{$field}` `{$field}` {$column}";

        //
        return $sql;
    }

    // retrive query to remove primary key
    public static function alterTableDropPrimaryKey($table)
    {
        //
        $sql = "ALTER TABLE `{$table}` DROP PRIMARY KEY";

        //
        return $sql;
    }

    /**
     *
     * @param  type   $f
     * @return string
     */
    public static function selectFields($fields, $tableAlias, &$join)
    {        
		//
        $join = "";
		
        //
        if (!$fields) {
            return '*';
        }

        //
        else if (is_string($fields)) {
            return $fields;
        }

        //
        else if (is_array($fields)) {

			//
			$aliasTable = array();
            
			//
            $selectFields = array();
					
            //
            foreach ($fields as $field => $definition) {
				
				//
				if (is_numeric($field)) {					
					$selectFields[] = static::selectFieldsSingletoneField($definition, $tableAlias);					
				} 
				
				//
				else if (is_array($definition)) {
					
					//
					$class		= $definition['Class'];
					
					//
					$aliasTable[$class] = isset($aliasTable[$class]) ? $aliasTable[$class]+1 : 1;
					
					//
					$joinAlias = $aliasTable[$class] > 1 ? $class.''.$aliasTable[$class] : $class;
					$joinTable = $definition['Table'];
					
					//
					$fieldFrom = $definition['FieldFrom'];
					$joinKey = $joinAlias.'.'.$definition['JoinKey'];
					$fieldTo = $joinAlias.'.'.$definition['FieldTo'];
					
					//
					$join .= " JOIN {$joinTable} AS {$joinAlias} ON {$joinKey} = {$fieldFrom}";
					
					//
					$selectFields[] = $fieldTo.' AS '.$field; 
				} 
				
				//
				else {
					$selectFields[] = $definition. ' AS '.$field;										
				} 				
            }

            //
            return implode(', ',$selectFields);
        }
    }
	
	/**
	 * 
	 * @param type $field
	 * @return type
	 */
	public static function selectFieldsSingletoneField($field,$tableAlias) {
				
		//
		if (preg_match('/^[a-z_][a-z0-9_]*$/i', $field)) {
			return $tableAlias ? $tableAlias.'.'.$field : $field;			
		} 
		
		//
		else {
			return $field;
		}		
	}
	
	/**
	 * Quote table or column names
	 * 
	 * 
	 */
	protected static function quote($name) {
		return '`'.$name.'`';
	} 
}

