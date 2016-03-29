<?php
/**
 * 
 * 
 */
namespace Javanile\SchemaDB\Parser;

/**
 *
 *
 *
 *
 */
class Mysql
{
    /**
     *
     *
     */
    const CLASSREGEX = '([A-Za-z_][0-9A-Za-z_]*(\\\\[A-Za-z_][0-9A-Za-z_]*)*)([^\*.]*)';

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

        // loop throh tables on the schema
        foreach($schema as &$table) {

            //
            foreach($table as $aspects) {

                //
                if (isset($aspects['Relation']) && $aspects['Relation'] == '*:*') {
                    $schema['Cioa'] = [
                        []
                    ];
                }
            }
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
     * @param  type   $notation
     * @param  type   $field
     * @param  type   $before
     * @return string
     */ 
    public static function
    getNotationAttributes($notation, $field=null, $before=null)
    {
        //
        $params = null;

		// get notation type 
        $type = static::getNotationType($notation, $params);
       
        // look-to type
        switch ($type) {

            //
            case 'schema':
                return $notation;

            // notation contain a field attributes written in json 
            case 'json':
                return static::getNotationAttributesJson
                    ($notation, $field, $before);

			// notation contain a date format string
            case 'date':
                return static::getNotationAttributesDate
                    ($notation, $field, $before);
				
			// 	
            case 'datetime':
                return static::getNotationAttributesDatetime
                    ($notation, $field, $before);
            
			// 	
			case 'timestamp':
                return static::getNotationAttributesTimestamp
                    ($notation, $field, $before);
            	  	
			// 
			case 'primary_key':
                return static::getNotationAttributesPrimaryKey
                    ($notation, $field, $before, $params);
               
			//
			case 'string':
                return static::getNotationAttributesString
                    ($notation, $field, $before);
               
            //
			case 'boolean':
                return static::getNotationAttributesBoolean
                    ($notation, $field, $before);

			//
			case 'integer':
                return static::getNotationAttributesInteger
                    ($notation, $field, $before);
                
			//
			case 'float': 
                return static::getNotationAttributesFloat
                    ($notation, $field, $before);
            
            //
			case 'double': 
                return static::getNotationAttributesDouble
                    ($notation, $field, $before);

			//
			case 'enum': 
                return static::getNotationAttributesEnum
                    ($notation, $field, $before);

			//
			case 'class': 
                return static::getNotationAttributesClass
                    ($notation, $field, $before, $params);

			//
			case 'vector': 
                return static::getNotationAttributesVector
                    ($notation, $field, $before, $params);

			//
			case 'matchs': 
                return static::getNotationAttributesMatchs
                    ($notation, $field, $before, $params);

			//
			case 'null': 
                return static::getNotationAttributesNull
                    ($notation, $field, $before);

			//
			default: trigger_error('Error parse type: '.$field.' ('.$type.')');
        }
    }

	/**
	 * 
	 * 
	 */
	private static function 
    getNotationAttributesJson($notation, $field, $before)
    {
		// decode json object into notation
		$json = json_decode(trim($notation,'<>'), true);

		// set with default attributes
		$attr = static::getNotationAttributesCommon($field, $before);
		
		// override default with json passed
		foreach ($json as $key => $value) {
			$attr[$key] = $value;
		}
        
        //
        return $attr;
	}
    
	/**
	 *
     * 
	 */
	private static function
    getNotationAttributesPrimaryKey($notation, $field, $before, $params)
    {
		//
		$aspects = static::getNotationAttributesCommon($field, $before);
		
        //
        $aspects['Type'] = isset($params[0])
                         ? 'int('.$params[0].')' 
                         : 'int(11)';
        
        //
        $aspects['Key'] = 'PRI';

        //
        $aspects['Null'] = 'NO';

        //
        $aspects['Default'] = '';

        //
		$aspects['Extra'] = 'auto_increment';

        //
        return $aspects;
	}
	
	/**
	 *
     * 
	 */
	private static function
    getNotationAttributesDate($notation, $field, $before)
    {		
		//
		$attributes = static::getNotationAttributesCommon($field, $before);
					
		//
		$attributes['Type'] = 'date';

        //
        $attributes['Default'] = $notation;

        //
        return $attributes;
	}
	
	/**
     * 
	 * 
	 */
	private static function
    getNotationAttributesDatetime($notation, $field, $before)
    {
		//
		$attributes = static::getNotationAttributesCommon($field, $before);
					
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
	private static function 
    getNotationAttributesTimestamp($notation, $field, $before)
    {
		//
		$attributes = static::getNotationAttributesCommon($field, $before);

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
        $attributes = static::getNotationAttributesCommon($field, $before);

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
     * 
	 */
	private static function
    getNotationAttributesBoolean($notation, $field, $before)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);
		
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
	private static function
    getNotationAttributesInteger($notation, $field, $before)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

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
     *
	 */
	private static function
    getNotationAttributesClass($notation, $field, $before, $params)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

        //
        $attributes['Type'] = 'int(11)';
        
        //
        $attributes['Class'] = $params[0];

        //
        $attributes['Relation']	= '1:1';

        //
        return $attributes;
	}
	
	/**
	 *
     *
	 */
	private static function
    getNotationAttributesVector($notation, $field, $before, $params)
    {
        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Relation'] = '1:*';
      
        //
        return $aspects;
	}

    /**
	 *
     *
	 */
	private static function
    getNotationAttributesMatchs($notation, $field, $before, $params)
    {
        var_Dump($params);

        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Relation'] = '*:*';

        //
        return $aspects;
	}

	/**
	 *
     * 
	 */
	private static function
    getNotationAttributesFloat($notation, $field, $before)
	{
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

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
     *
	 */
	private static function
    getNotationAttributesDouble($notation, $field, $before)
	{
        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Null'] = 'NO';

        //
        $aspects['Type'] = 'double(10,4)';

        //
        $aspects['Default']	= (double) $notation;

        //
        return $aspects;
	}

	/**
	 * 
	 */
	private static function
    getNotationAttributesEnum($notation, $field, $before)
    {
        //
        $enum = static::parseNotationEnum($notation);

        //
        if (!$enum) {
            return static::getNotationAttributes('', $field, $before);
        }

        //
		$aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Enum'] = $enum;

		//
		$aspects['Default'] = $enum[0];
        
		//
		$aspects['Null'] = in_array(null, $enum) ? 'YES' : 'NO';
        
		//
		$t = array();
        
		//
		foreach ($enum as $i) {
			if ($i !== null) {
				$t[] = "'{$i}'";
			}
		}

		//
		$aspects['Type'] = 'enum('.implode(',',$t).')';

        //
        return $aspects;
	}

    /**
     *
     *
     */
    private static function parseNotationEnum($notation)
    {
        //
        if (is_string($notation)) {

            //
            $notation = json_decode(trim($notation, '<>'));

            //
            if (json_last_error()) { return null; }
        }

        //
        return $notation;
    }

	/**
	 *
     * 
	 */
	private static function 
    getNotationAttributesNull($notation, $field, $before)
    {
		//
		$aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Type'] = 'varchar(255)';

        //
        $aspects['Default'] = $notation;

        //
        return $aspects;
	}
	
	/**
	 * 
	 */
	private static function 
    getNotationAttributesCommon($field, $before)
    {
        //
        $aspects = array(
			'Key'     => '',
			'Type'    => '',
			'Null'    => 'YES',
			'Extra'   => '',
            'Default' => '',
		);

        //
        if (!is_null($field)) {
            $aspects['Field'] = $field;
        }

        //
        if (!is_null($before)) {
            $aspects['First'] = !$before;
			$aspects['Before'] = $before;
        }

		//
		return $aspects;
	}
	
    /**
	 * 
	 * 
	 * @param type $notation
	 * @return string
	 */
    public static function getNotationType($notation, &$params=null)
    {
        //
        $type = gettype($notation);

        //
        $params = null;

        //
        switch ($type) {

            //
            case 'string': return static::getNotationTypeString($notation, $params);
                				
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
	private static function getNotationTypeString($notation, &$params)
    {
        //
        $matchs = null;

		// 
		$params = null;
        
		//
		if (preg_match('/^<<#([a-z_]+)>>$/i', $notation, $matchs)) {
            return $matchs[1];
		}

        //
        else if (preg_match('/^<<primary key ([1-9][0-9]*)>>$/', $notation, $matchs)) {
            $params = array_slice($matchs, 1);
            return 'primary_key';
        }
		
		//
		else if (static::pregMatchClass($notation, $matchs)) {
            $params[0] = $matchs[1];
            return 'class';
		} 

		//
		else if (static::pregMatchVector($notation, $matchs)) {
            return 'vector';
		} 

        //
		else if (static::pregMatchMatchs($notation, $matchs)) {
			return 'matchs';
		}

		//
		else if (preg_match('/^<<\{.*\}>>$/si', $notation)) {
			return 'json';
		}

        //
		else if (preg_match('/^<<\[.*\]>>$/si', $notation)) {
			return 'enum';
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
     */
    public static function pregMatchClass($notation, &$matchs)
    {
        //
        return preg_match(
            '/^<<'.static::CLASSREGEX.'>>$/',
            $notation,
            $matchs
        );
    }

    /**
     *
     *
     */
    public static function pregMatchVector($notation, &$matchs)
    {
        //
        return preg_match(
            '/^<<'.static::CLASSREGEX.'\*>>$/',
            $notation,
            $matchs
        );
    }

    /**
     *
     *
     */
    public static function pregMatchMatchs($notation, &$matchs)
    {
        //
        return preg_match(
            '/^<<'.static::CLASSREGEX.'\*\*>>$/',
            $notation,
            $matchs
        );
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
        $type = static::getNotationType($notation, $params);

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
			case 'matchs': return null;

            //
			case 'array': return null;

			//
            case 'enum': return !is_string($notation) && isset($notation[0]) && !is_null($notation[0]) ? $notation[0] : null;

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

