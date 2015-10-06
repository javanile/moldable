<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * A collection of MySQL stataments builder
 * used with mysql query template and place-holder replacing
 */
class MysqlComposer
{
    ##
    private static $default = array(
        'attributes' => array(
            'type' => 'int(10)',
        ),
    );

    ##
    public static function columnDefinition($d,$o=true)
    {
        ##
        $t = isset($d['Type']) ? $d['Type'] : static::$default['attributes']['type'];
        $u = isset($d['Null']) && ($d['Null']=="NO" || !$d['Null']) ? 'NOT NULL' : 'NULL';
        $l = isset($d['Default']) && $d['Default'] ? "DEFAULT '$d[Default]'" : '';
        $e = isset($d['Extra']) ? $d['Extra'] : '';
        $p = isset($d['Key']) && $d['Key'] == 'PRI' ? 'PRIMARY KEY' : '';

        ##
        $q = $t.' '.$u.' '.$l.' '.$e.' '.$p;

        ##
        if ($o) {
            $f = isset($d["First"])&&$d["First"] ? 'FIRST' : '';
            $b = isset($d["Before"])&&$d["Before"] ? 'AFTER '.$d["Before"] : '';
            $q.= ' '.$f.' '.$b;
        }

        ##

        return $q;
    }

    /**
     * Prepare sql code to create a table
     *
     * @param  string $t The name of table to create
     * @param  array  $s Skema of the table contain column definitions
     * @return string Sql code statament of CREATE TABLE
     */
    public static function &createTable($table, &$schema)
    {
        ##
        $e = array();

        ## loop throut schema
        foreach ($schema as $f => $d) {

            ##
            if (is_numeric($f) && is_string($d)) {

                ##
                $f = $d;

                ##
                $d = array();
            }

            ##
            $e[] = $f.' '.static::columnDefinition($d,false);
        }

        ## implode
        $i = implode(',',$e);

        ## template sql to create table
        $sql = "CREATE TABLE {$table} ({$i})";

        ## return the sql
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
        ##
        $column = Mysql::columnDefinition($attributes);

        ##
        $sql = "ALTER TABLE {$table} ADD {$field} {$column}";

        ##
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
        ##
        $column = static::columnDefinition($attributes);

        ##
        $sql = "ALTER TABLE {$table} CHANGE {$field} {$field} {$column}";

        ##
        return $sql;
    }

    ## retrive query to remove primary key
    public static function alterTableDropPrimaryKey($table)
    {
        ##
        $sql = "ALTER TABLE {$table} DROP PRIMARY KEY";

        ##
        return $sql;
    }

    /**
     *
     * @param  type   $f
     * @return string
     */
    public static function selectFields($fields, $tableAlias, &$join)
    {
        ##
        $join = "";
		
		

        ##
        if (is_null($fields)) {
            return '*';
        }

        ##
        else if (is_string($fields)) {
            return $fields;
        }

        ##
        else if (is_array($fields)) {

			##
			$aliasTable = array();
            
			##
            $selectFields = array();
					
            ##
            foreach ($fields as $field => $definition) {
				
				##
				if (is_numeric($field)) {					
					$selectFields[] = static::selectFieldsSingletoneField($definition, $tableAlias);					
				} 
				
				##
				else if (is_array($definition)) {
					
					##
					$class		= $definition['Class'];
					
					##
					$aliasTable[$class] = isset($aliasTable[$class]) ? $aliasTable[$class]+1 : 1;
					
					##
					$alias		= $aliasTable[$class] > 1 ? $class.''.$aliasTable[$class] : $class;
					$table		= $definition['Table'];
					$fieldFrom	= $definition['FieldFrom'];
					$joinKey	= $alias.'.'.$definition['JoinKey'];
					$fieldTo	= $alias.'.'.$definition['FieldTo'];
					
					##
					$join .= " JOIN {$table} AS {$alias} ON {$joinKey} = {$fieldFrom}";
					
					##
					$selectFields[] = $fieldTo.' AS '.$field; 
				} 
				
				##
				else {
					$selectFields[] = $definition. ' AS '.$field;										
				} 				
            }

            ##
            return implode(', ',$selectFields);
        }
    }
	
	/**
	 * 
	 * @param type $field
	 * @return type
	 */
	public static function selectFieldsSingletoneField($field,$tableAlias) {
				
		##
		if (preg_match('/^[a-z_][a-z0-9_]*$/i', $field)) {
			return $tableAlias ? $tableAlias.'.'.$field : $field;			
		} 
		
		##
		else {
			return $field;
		}		
	}
}

