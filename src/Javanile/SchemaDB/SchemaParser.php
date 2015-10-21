<?php

/**
 * 
 * 
 */
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
    public static function parse(&$schema)
    {		
        // loop throh tables on the schema
        foreach($schema as &$table) {

            // parse each table
            static::parseTable($table);
        }
    }

    /**
	 * Parse table schema to sanitize end explod implicit info
	 * 
	 * @param type $schema
	 * @return type
	 */
    public static function parseTable(&$table)
    {        
        // for first field no have before 
        $before = false;

        // loop throuh fields on table
        foreach ($table as $field => &$notation) {

            // parse notation for this field
            $notation = static::getNotationAttributes($notation, $field, $before);

            // update before for next
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
    public static function getNotationAttributes($notation, $field=null, $before=null)
    {        		
		// get notation type 
        $type = static::getNotationType($notation);
		
        // look-to type
        switch ($type) {

            //
            case 'schema': return $notation;

            // notation contain a field attributes written in json 
            case 'json': return static::getNotationAttributesJson($notation, $field, $before);

			// notation contain a date format string
            case 'date': return static::getNotationAttributesDate($notation, $field, $before);
				
			// 	
            case 'datetime': return static::getNotationAttributesDatetime($notation, $field, $before);
            
			// 	
			case 'timestamp': return static::getNotationAttributesTimestamp($notation, $field, $before);
            	  	
			// 
			case 'primary_key': return static::getNotationAttributesPrimaryKey($notation, $field, $before);
               
			//
			case 'string': return static::getNotationAttributesString($notation, $field, $before);
               
            //
			case 'boolean': return static::getNotationAttributesBoolean($notation, $field, $before);

			//
			case 'integer': return static::getNotationAttributesInteger($notation, $field, $before);
                
			//
			case 'float': return static::getNotationAttributesFloat($notation, $field, $before);
            
            //
			case 'double': return static::getNotationAttributesDouble($notation, $field, $before);

			//
			case 'enum': return static::getNotationAttributesEnum($notation, $field, $before);

			//
			case 'class': return static::getNotationAttributesClass($notation, $field, $before);

			//
			case 'vector': return static::getNotationAttributesVector($notation, $field, $before);

			//
			case 'null': return static::getNotationAttributesNull($notation, $field, $before);

			//
			default: trigger_error('Error parse type: '.$field.' ('.$type.')');
        }
    }

	/**
	 * 
	 * 
	 */
	private static function getNotationAttributesJson($notation, $field, $before) {

		// decode json object into notation
		$json = json_decode(trim($notation,'<>'), true);

		// set with default attributes
		$attributes = static::getNotationAttributesDefault($field, $before);
		
		// override default with json passed
		foreach ($json as $key => $value) {
			$attributes[$key] = $value;
		}

        //
        return $attributes;
	}
    
	/**
	 * 
	 */
	private static function
    getNotationAttributesPrimaryKey($notation, $field, $before) {
	
		//
		$attributes = static::getNotationAttributesDefault($field, $before);
		
        //
        $attributes['Type']	= 'int(11)';
        			
        //
        $attributes['Key'] = 'PRI';

        //
        $attributes['Null'] = 'NO';

        //
        $attributes['Default'] = '';

        //
		$attributes['Extra'] = 'auto_increment';

        //
        return $attributes;
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesDate($notation, $field, $before) {
		
		//
		$attributes = static::getNotationAttributesDefault($field, $before);
					
		//
		$attributes['Type'] = 'date';

        //
        $attributes['Default'] = $notation;

        //
        return $attributes;
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesDatetime($notation, $field, $before) {
		
		//
		$attributes = static::getNotationAttributesDefault($field, $before);
					
		//
		$attributes['Type'] = 'datetime';

        //
        $attributes['Default'] = $notation;

        //
        return $attributes;
	}

    /**
	 *
	 */
	private static function getNotationAttributesTimestamp($notation, $field, $before) {

		//
		$attributes = static::getNotationAttributesDefault($field, $before);

		//
		$attributes['Type'] = 'timestamp';

        //
        $attributes['Null'] = 'NO';

        //
        $attributes['Default'] = 'CURRENT_TIMESTAMP';

        //
        return $attributes;
	}

	/**
	 * 
	 */
	private static function getNotationAttributesString($notation, $field, $before) {
		
        //
        $attributes = static::getNotationAttributesDefault($field, $before);

		//
		$attributes['Type'] = 'varchar(255)';

        //
        $attributes['Default'] = $notation;
        
        //
        $attributes['Null'] = 'NO';

        //
        return $attributes;
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesBoolean($notation, $field, $before) {

        //
        $attributes = static::getNotationAttributesDefault($field, $before);
		
		//
		$attributes['Type'] = 'tinyint(1)';
		
		//
		$attributes['Default'] = (int) $notation;
          
		//
		$attributes['Null'] = 'NO';

        //
        return $attributes;
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesInteger($notation, $field, $before) {

        //
        $attributes = static::getNotationAttributesDefault($field, $before);

		//
		$attributes['Type']	= 'int(11)';
        
        //
        $attributes['Default']	= (int) $notation;

        //
        $attributes['Null'] = 'NO';
			        
        //
        return $attributes;
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesClass($notation, $field, $before) {

		//
		$notation = array(
			'Class'		=> trim($notation,'<>'),
			'Relation'	=> '1:1',
			'Field'		=> $field,
			'Before'	=> $before,
			'First'		=> !$before,
			'Type'		=> 'int(11)',
			'Key'		=> '',
			'Default'	=> null,
			'Null'		=> 'YES',
			'Extra'		=> '',
		);
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesVector($notation, $field, $before) {

		//
		$notation = array(
			'Class'		=> trim($notation,'<*>'),
			'Relation'	=> '1:*',
			'Field'		=> $field,
			'Before'	=> $before,
			'First'		=> !$before,
			'Type'		=> 'int(11)',
			'Key'		=> '',
			'Default'	=> null,
			'Null'		=> 'YES',
			'Extra'		=> '',
		);
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesFloat($notation, $field, $before)
	{
        //
        $attributes = static::getNotationAttributesDefault($field, $before);

        //
        $attributes['Null']	= 'NO';
        
        //
        $attributes['Type']	= 'float(12,2)';

        //
        $attributes['Default']	= (float) $notation;

        //
        return $attributes;
	}

    /**
	 *
	 */
	private static function getNotationAttributesDouble($notation, $field, $before)
	{
        //
        $attributes = static::getNotationAttributesDefault($field, $before);

        //
        $attributes['Null']	= 'NO';

        //
        $attributes['Type']	= 'double(10,4)';

        //
        $attributes['Default']	= (double) $notation;

        //
        return $attributes;
	}

	/**
	 * 
	 */
	private static function
    getNotationAttributesEnum($notation, $field, $before)
    {
        //
		$attributes = static::getNotationAttributesDefault($field, $before);
				
		//
		$attributes['Default'] = $notation[0];
        
		//
		$attributes['Null'] = in_array(null, $notation) ? 'YES' : 'NO';
        
		//
		$t = array();
        
		//
		foreach ($notation as $i) {
			if ($i !== null) {
				$t[] = "'{$i}'";
			}
		}

		//
		$attributes['Type'] = 'enum('.implode(',',$t).')';

        //
        return $attributes;
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesNull($notation, $field, $before) {
        
		//
		$attributes = static::getNotationAttributesDefault($field, $before);

        //
        $attributes['Type'] = 'varchar(255)';

        //
        $attributes['Default'] = $notation;

        //
        return $attributes;
	}
	
	/**
	 * 
	 */
	private static function getNotationAttributesDefault($field, $before) {

        //
        $attributes = array(
			'Key' => '',
			'Type' => '',
			'Null' => 'YES',
			'Extra' => '',
			'Default' => 'NO',
		);

        //
        if (!is_null($field)) {
            $attributes['Field'] = $field;
        }

        //
        if (!is_null($before)) {
            $attributes['First'] = !$before;
			$attributes['Before']	= $before;
        }

		//
		return $attributes;
	}
	
    /**
	 * 
	 * 
	 * @param type $notation
	 * @return string
	 */
    public static function getNotationType(&$notation)
    {
        //
        $type = gettype($notation);

        //
        switch ($type) {

            //
			case 'string': return static::getNotationTypeString($notation);
                				
			//
			case 'array': return static::getNotationTypeArray($notation);
				
            //
			case 'integer': return 'integer';

            //
			case 'double': return 'float';

			//
			case 'boolean': return 'boolean';

			//
			case 'NULL': return 'null';
        }
    }

	/**
	 * 
	 * @param type $notation
	 */
	private static function getNotationTypeString(&$notation) {
				
		// 
		$macro = null;
		
		//
		if (preg_match('/^<<#([a-z_]+)>>$/i', $notation, $macro)) {
			return $macro[1];
		} 
		
		//
		else if (preg_match('/^<<[A-Za-z_][0-9A-Za-z_]*>>$/i', $notation)) {
			return 'class';
		} 

		//
		else if (preg_match('/^<<[A-Za-z_][0-9A-Za-z_]*\*>>$/i', $notation)) {
			return 'vector';
		} 

		//
		else if (preg_match('/^<<\{.*\}>>$/i', $notation)) {
			return 'json';
		} 

		//
		else if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/', $notation)) {
			return 'datetime';
		} 

		//
		else if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/', $notation)) {
			return 'date';
		} 

		//
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
		
		// 
		if ($notation && $notation == array_values($notation)) {
			return 'enum';
		} 

		// 
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
    public static function getNotationValue($notation)
    {
        //
        $type = static::getNotationType($notation);

        //
        switch ($type) {

            //
			case 'integer': return (int) $notation;

            //
			case 'boolean': return (boolean) $notation;

            //
			case 'primary_key': return null;

            //
			case 'string': return (string) $notation;

            //
			case 'float': return (float) $notation;

            //
			case 'double': return (double) $notation;
  
            //
			case 'class': return null;

			//
			case 'vector': return null;

            //
			case 'array': return null;

			//
			case 'enum': return isset($notation[0]) && !is_null($notation[0]) ? $notation[0] : null;

            //
			case 'time': return static::parseTime($notation);

            //
			case 'date': return static::parseDate($notation);

            //
			case 'datetime': return static::parseDatetime($notation);

            //
			case 'timestamp': return null;

            //
			case 'schema': return null;

            //
			case 'column': return null;

			//
			case 'json': return null;
				
			//
			case 'null': return null;

            //
            default: trigger_error("No PSEUDOTYPE value for '{$type}' => '{$notation}'",E_USER_ERROR);
        }
    }

    // printout database status/info
    public static function parseDate($date)
    {
        //
        if ($date != '0000-00-00') {
            return @date('Y-m-d', @strtotime(''.$date));
        } else {
            return null;
        }
    }

    // printout database status/info
    public static function parseDatetime($datetime)
    {
        if ($datetime != '0000-00-00 00:00:00') {
            return @date('Y-m-d H:i:s', @strtotime(''.$datetime));
        } else {
            return null;
        }
    }
}

