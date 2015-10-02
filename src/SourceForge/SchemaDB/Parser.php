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
class Parser
{
    /**
	 * parse a multi-table schema to sanitize end explode implicit info
	 * 
	 * @param type $schema
	 * @return type
	 */
    public static function parseSchemaDB(&$schema)
    {		
        ## loop throh tables on the schema
        foreach($schema as &$table) {

            ## parse each table
            static::parseSchemaTable($table);
        }
    }

    /**
	 * Parse table schema to sanitize end explod implicit info
	 * 
	 * @param type $schema
	 * @return type
	 */
    private static function parseSchemaTable(&$table)
    {        
        ## for first field no have before 
        $before = null;

        ## loop throuh fields on table
        foreach ($table as $field => &$notation) {

            ## parse notation for this field
            static::parseSchemaTableField($field, $notation, $before);

            ## update before for next
            $before = $field;
        }       
    }

    /**
     * Parse notation of a field
     *
     * @param  type   $field
     * @param  type   $notation
     * @param  type   $before
     * @return string
     */
    public static function &parseSchemaTableField($field, &$notation, $before=null)
    {        		
        ## get notation type 
        $type = static::getNotationType($notation);

        ## look-to type
        switch ($type) {

            ## notation contain a field attributes written in json 
			case 'json': static::parseSchemaTableFieldJson($field, $notation, $before); break;

			## notation contain a date format string
			case 'date': static::parseSchemaTableFieldDate($field, $notation, $before); break;
				
			## 	
			case 'datetime': static::parseSchemaTableFieldDatetime($field, $notation, $before); break;
               
			## 
			case 'primary_key': static::parseSchemaTableFieldPrimaryKey($field, $notation, $before); break;
               
			##
			case 'string': static::parseSchemaTableFieldString($field, $notation, $before); break;
               
            ##
			case 'boolean': static::parseSchemaTableFieldBoolean($field, $notation, $before); break;                

			##
			case 'integer': static::parseSchemaTableFieldInteger($field, $notation, $before); break;   
                
			##
			case 'float': static::parseSchemaTableFieldFloat($field, $notation, $before); break;   
                
			##
			case 'enum': static::parseSchemaTableFieldEnum($field, $notation, $before); break;                   
        }
		
		##
		return $notation;
    }

	/**
	 * 
	 */
	private static function parseSchemaTableFieldJson($field, &$notation, $before) {
		
		##
		$notation = json_decode(trim($notation,'<>'), true);
		
		##
		$notation['Field'] = $field;
		
		##
		$notation['Before'] = $before;
	}
    
	/**
	 * 
	 */
	private static function parseSchemaTableFieldDate($field, &$notation, $before) {
		
		##
		$notation = array();
			
		##
		$notation['Field'] = $field;
		
		##
		$notation['Before'] = $before;
		
		##
		$notation['Type'] = 'date';
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldPrimaryKey($field, &$notation, $before) {
	
		##
		$notation = array();
		
		##
		$notation['Field'] = $field;
		
		##
		$notation['Before'] = $before;
		
		##
		$notation['Type'] = 'int(10)';
                
		##
		$notation['Null'] = 'NO';
                
		##
		$notation['Key'] = 'PRI';
        
		##
		$notation['Extra'] = 'auto_increment';
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldDatetime($field, &$notation, $before) {
		
		##
		$notation = array();
			
		##
		$notation['Field'] = $field;
		
		##
		$notation['Before'] = $before;
		
		##
		$notation['Type'] = 'date';
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldString($field, &$notation, $before) {
		
		##
		$notation = array(
			'Field'		=> $field,
			'Before'	=> $before,
			'Type'		=> 'varchar(255)',
			'Key'		=> '',
            'Default'	=> (string) $notation,
            'Extra'		=> '',
            'First'		=> !$before,
			'Null'		=> 'NO',
		);		
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldBoolean($field, &$notation, $before) {
	
		##
		$notation = array();
		
		##
		$notation['Field'] = $field;
		
		##
		$notation['Before'] = $before;
		
		##
		$notation['Type'] = 'tinyint(1)';
		
		##
		$notation['Default'] = (int) $notation;
          
		##
		$notation['Null'] = 'NO';                
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldInteger($field, &$notation, $before) {
		
		$notation = array();
		##
		$notation['Field'] = $field;
		
		##
		$notation['Before'] = $before;
		
		$notation['Type'] = 'int(10)';
		$notation['Default'] = (int) $notation;
		$notation['Null'] = 'NO';
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldFloat($field, &$notation, $before) {
	
		##
		$notation = array();
		
		##
		$notation['Field'] = $field;
		
		##
		$notation['Before'] = $before;
		
		##
		$notation ['Type'] = 'float(12,2)';
        
		##
		$notation ['Default'] = (float) $notation;
        
		##
		$notation ['Null'] = 'NO';          
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldEnum($field, &$notation, $before) {
	
			 $d['Default'] = $notation[0];
                $d['Null'] = in_array(null,$notation) ? 'YES' : 'NO';
                $t = array();
                foreach ($notation as $i) {
                    if ($i!==null) {
                        $t[] = "'{$i}'";
                    }
                }
                $d['Type'] = 'enum('.implode(',',$t).')';
	}
	
    /**
	 * 
	 * 
	 * @param type $notation
	 * @return string
	 */
    public static function getNotationType(&$notation)
    {
        ##
        $type = gettype($notation);

        ##
        switch ($type) {

            ##
			case 'string': return static::getNotationTypeString($notation);
                				
			##
			case 'array': return static::getNotationTypeArray($notation);                
				
            ##
			case 'NULL': return 'string';

            ##
			case 'boolean': return 'boolean';

            ##
			case 'integer': return 'integer';

            ##
			case 'double': return 'float';
        }
    }

	/**
	 * 
	 * @param type $notation
	 */
	private static function getNotationTypeString(&$notation) {
		
		##
		if (preg_match('/^<<\[A-Za-z_][0-9A-Za-z_]*\>>$/i', $notation)) {
			return 'class';
		} 

		##
		else if (preg_match('/^<<\{.*\}>>$/i', $notation)) {
			return 'json';
		} 

		##
		else if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/', $notation)) {
			return 'datetime';
		} 

		##
		else if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/', $notation)) {
			return 'date';
		} 

		##
		else {
			return 'string';
		}		
	}
	
	/**
	 * 
	 * 
	 * @param type $notation
	 * @return string
	 */
	private static function getNotationTypeArray(&$notation) {
		
		## 
		if ($notation && $notation == array_values($notation)) {
			return 'enum';
		} 

		## 
		else {
			return 'schema';
		}
	}
	
    /**
	 * Retrieve value of a parsable notation
	 * Value rapresent ...
	 * 
	 * @param type $notation
	 * @return type
	 */
    public static function getNotaionValue($notation)
    {
        ##
        $t = static::getNotationType($notation);

        ##
        switch ($t) {

            ##
			case 'integer': return (int) $notation;

            ##
			case 'boolean': return (boolean) $notation;

            ##
			case 'primary_key': return NULL;

            ##
			case 'string': return (string) $notation;

            ##
			case 'float': return (float) $notation;

            ##
			case 'class': return NULL;

            ##
			case 'array': return NULL;

            ##
			case 'date': return static::parseDate($notation);

            ##
			case 'datetime': return static::parseDatetime($notation);

            ##
			case 'schema': return null;

            ##
			case 'column': return null;

			##
			case 'json': return null;

            ##
            default: trigger_error("No PSEUDOTYPE value for '{$t}' => '{$notation}'",E_USER_ERROR);
        }
    }

    ## printout database status/info
    public static function parseDate($date)
    {
        ##
        if ($date != '0000-00-00') {
            return @date('Y-m-d', @strtotime(''.$date));
        } else {
            return null;
        }
    }

    ## printout database status/info
    public static function parseDatetime($datetime)
    {
        if ($datetime != '0000-00-00 00:00:00') {
            return @date('Y-m-d H:i:s', @strtotime(''.$datetime));
        } else {
            return null;
        }
    }

    ##
    public static function escape($value)
    {		
		##
        return stripslashes($value);
    }

    ##
    public static function encode($value)
    {
        ##
        $t = gettype($value);

        ##
        if ($t == 'double') {
            $v = number_format($value,2,'.','');
        }

        ##
        return $v;
    }
}

