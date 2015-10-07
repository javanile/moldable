<?php

/*\
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 *
 *
 *
 *
 */
class SchemaParser
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
    public static function parseSchemaTable(&$table)
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
    public static function parseSchemaTableField($field, &$notation, $before=null)
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
			case 'timestamp': static::parseSchemaTableFieldTimestamp($field, $notation, $before); break;
            	  	
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

			##
			case 'class': static::parseSchemaTableFieldClass($field, $notation, $before); break;                   

			##
			case 'vector': static::parseSchemaTableFieldVector($field, $notation, $before); break;                   

			##
			case 'null': static::parseSchemaTableFieldNull($field, $notation, $before); break; 
			
			##
			default: trigger_error('Error parse type: '.$field.'('.$type.')');
        }
		
		##
		return $notation;
    }

	/**
	 * 
	 * 
	 */
	private static function parseSchemaTableFieldJson($field, &$notation, $before) {

		## decode json object into notation
		$json = json_decode(trim($notation,'<>'), true);

		## set with default attributes
		$notation = static::parseSchemaTableFieldDefault($field, $before);
		
		## override default with json passed
		foreach ($json as $key => $value) {
			$notation[$key] = $value;
		}
	}
    
	/**
	 * 
	 */
	private static function parseSchemaTableFieldPrimaryKey($field, &$notation, $before) {
	
		##
		$notation = array(
			'Field'		=> $field,
			'Before'	=> $before,
			'First'		=> !$before,
			'Type'		=> 'int(10)',
			'Default'	=> '',
			'Null'		=> 'NO',
			'Key'		=> 'PRI',
			'Extra'		=> 'auto_increment',
		);		
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldDate($field, &$notation, $before) {
		
		##
		$notation = static::parseSchemaTableFieldDefault($field, $before);
					
		##
		$notation['Type'] = 'datetime';
	}
	
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldDatetime($field, &$notation, $before) {
		
		##
		$notation = static::parseSchemaTableFieldDefault($field, $before);
					
		##
		$notation['Type'] = 'datetime';
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

		##
		$notation = array(
			'Field'		=> $field,
			'Before'	=> $before,
			'First'		=> !$before,
			'Type'		=> 'int(10)',
			'Key'		=> '',
			'Default'	=> (int) $notation,
			'Null'		=> 'NO',
			'Extra'		=> '',
		);
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldClass($field, &$notation, $before) {

		##
		$notation = array(
			'Class'		=> trim($notation,'<>'),
			'Relation'	=> '1:1',
			'Field'		=> $field,
			'Before'	=> $before,
			'First'		=> !$before,
			'Type'		=> 'int(10)',
			'Key'		=> '',
			'Default'	=> null,
			'Null'		=> 'YES',
			'Extra'		=> '',
		);
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldVector($field, &$notation, $before) {

		##
		$notation = array(
			'Class'		=> trim($notation,'<*>'),
			'Relation'	=> '1:*',
			'Field'		=> $field,
			'Before'	=> $before,
			'First'		=> !$before,
			'Type'		=> 'int(10)',
			'Key'		=> '',
			'Default'	=> null,
			'Null'		=> 'YES',
			'Extra'		=> '',
		);
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldFloat($field, &$notation, $before) {
	
		##
		$notation = array(
			'Field'		=> $field,
			'Before'	=> $before,
			'First'		=> !$before,
			'Type'		=> 'float(12,2)',
			'Default'	=> (float) $notation,
			'Null'		=> 'NO',
			'Key'		=> '',
			'Extra'		=> '',
		);			
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
	 */
	private static function parseSchemaTableFieldNull($field, &$notation, $before) {
	
		##
		$notation = array(
			'Field'		=> $field,
			'Before'	=> $before,
			'Type'		=> 'varchar(255)',
			'Key'		=> '',
            'Default'	=> '',
            'Extra'		=> '',
            'First'		=> !$before,
			'Null'		=> 'YES',
		);		
	}
	
	/**
	 * 
	 */
	private static function parseSchemaTableFieldDefault($field, $before) {
		
		##
		return array(
			'Field'		=> $field,
			'First'		=> !$before,
			'Before'	=> $before,
			'Null'		=> 'YES',
			'Default'	=> '',
			'Key'		=> '',
			'Type'		=> '',
			'Extra'		=> '',
		);		
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
			case 'integer': return 'integer';

            ##
			case 'double': return 'float';

			##
			case 'boolean': return 'boolean';

			##
			case 'NULL': return 'null';
        }
    }

	/**
	 * 
	 * @param type $notation
	 */
	private static function getNotationTypeString(&$notation) {
				
		## 
		$macro = null;
		
		##
		if (preg_match('/^<<#([a-z_]+)>>$/i', $notation, $macro)) {
			return $macro[1];
		} 
		
		##
		else if (preg_match('/^<<[A-Za-z_][0-9A-Za-z_]*>>$/i', $notation)) {
			return 'class';
		} 

		##
		else if (preg_match('/^<<[A-Za-z_][0-9A-Za-z_]*\*>>$/i', $notation)) {
			return 'vector';
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
        $type = static::getNotationType($notation);

        ##
        switch ($type) {

            ##
			case 'integer': return (int) $notation;

            ##
			case 'boolean': return (boolean) $notation;

            ##
			case 'primary_key': return null;

            ##
			case 'string': return (string) $notation;

            ##
			case 'float': return (float) $notation;

            ##
			case 'class': return null;

			##
			case 'vector': return null;

            ##
			case 'array': return null;

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
			case 'null': return null;

            ##
            default: trigger_error("No PSEUDOTYPE value for '{$type}' => '{$notation}'",E_USER_ERROR);
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

