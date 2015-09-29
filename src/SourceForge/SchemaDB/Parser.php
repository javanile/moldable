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
    ##
    private static $default = array(
        'attribute' => array(
            'type'		=> 'int(10)',
            'null'		=> 'YES',
            'key'		=> '',
            'default'	=> '',
            'extra'		=> '',
        ),
    );

    ## parse a multi-table schema to sanitize end explode implicit info
    public static function parseSchema($schema)
    {
        ##
        $s = array();

        ##
        foreach ($schema as $t => $f) {

            ##
            $s[$t] = static::parseSchemaTable($f);
        }

        ##

        return $s;
    }

    ## parse table schema to sanitize end explod implicit info
    public static function parseSchemaTable($schema)
    {
        ##
        $s = array();

        ##
        $b = false;

        ##
        foreach ($schema as $f=>$d) {

            ##
            $s[$f] = static::parseSchemaTableColumn($d,$f,$b);

            ##
            $b = $f;
        }

        ##

        return $s;
    }

    /**
     * Build mysql column attribute set
     *
     * @param  type   $notation
     * @param  type   $field
     * @param  type   $before_field
     * @return string
     */
    public static function parseSchemaTableColumn($notation,$field=null,$before_field=null)
    {
        ## default schema of a column
        $d = array(
            'Field'		=> $field,
            'Type'		=> static::$default['attribute']['type'],
            'Null'		=> static::$default['attribute']['null'],
            'Key'		=> static::$default['attribute']['key'],
            'Default'	=> static::$default['attribute']['default'],
            'Extra'		=> static::$default['attribute']['extra'],
            'Before'	=> $before_field,
            'First'		=> !$before_field,
        );

        ##
        $t = static::getType($notation);

        ##
        switch ($t) {

            ##
            case 'schema':
                foreach (static::getSchema($notation) as $a=>$v) {
                    $d[$a] = $v;
                }
                break;

            case 'date':
                $d['Type'] = 'date';
                break;

            case 'datetime':
                $d['Type'] = 'datetime';
                break;

            case 'primary_key':
                $d['Type'] = 'int(10)';
                $d['Null'] = 'NO';
                $d['Key'] = 'PRI';
                $d['Extra'] = 'auto_increment';
                break;

            case 'string':
                $d['Type'] = 'varchar(255)';
                break;

            case 'boolean':
                $d['Type'] = 'tinyint(1)';
                $d['Default'] = (int) $notation;
                $d['Null'] = 'NO';
                break;

            case 'int':
                $d['Type'] = 'int(10)';
                $d['Default'] = (int) $notation;
                $d['Null'] = 'NO';
                break;

            case 'float':
                $d['Type'] = 'float(12,2)';
                $d['Default'] = (int) $notation;
                $d['Null'] = 'NO';
                break;

            case 'array':
                $d['Default'] = $notation[0];
                $d['Null'] = in_array(null,$notation) ? 'YES' : 'NO';
                $t = array();
                foreach ($notation as $i) {
                    if ($i!==null) {
                        $t[] = "'{$i}'";
                    }
                }
                $d['Type'] = 'enum('.implode(',',$t).')';
                break;
        }

        return $d;
    }

    /**
	 * 
	 * 
	 * @param type $notation
	 * @return string
	 */
    public static function getType($notation)
    {
        ##
        $type = gettype($notation);

        ##
        switch ($type) {

            ##
			case 'string': return static::getTypeString($notation);
                				
			##
			case 'array': return static::getTypeArray($notation);                
				
            ##
			case 'NULL': return 'string';

            ##
			case 'boolean': return 'boolean';

            ##
			case 'integer': return 'int';

            ##
			case 'double': return 'float';
        }
    }

	/**
	 * 
	 * @param type $notation
	 */
	private static function getTypeString($notation) {
		
		##
		if (preg_match('/^<\{[A-Za-z_][0-9A-Za-z_]*\}>$/i',$notation)) {
			return 'class';
		} 

		##
		elseif (preg_match('/^<\{.*\}>$/i',$notation)) {
			return 'schema';
		} 

		##
		elseif (preg_match('/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]/',$notation)) {
			return 'datetime';
		} 

		##
		elseif (preg_match('/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]/',$notation)) {
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
	private static function getTypeArray($notation) {
		
		##
		if ($notation && $notation == array_values($notation)) {
			return 'array';
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
    public static function getValue($notation)
    {
        ##
        $t = static::getType($notation);

        ##
        switch ($t) {

            ##
			case 'int': return (int) $notation;

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
			case 'date': return static::parse_date($notation);

            ##
			case 'datetime': return static::parse_datetime($notation);

            ##
			case 'schema': return null;

            ##
			case 'column': return null;

            ##
            default: trigger_error("No PSEUDOTYPE value for '{$t}' => '{$notation}'",E_USER_ERROR);
        }
    }

    /**
	 * Parse notation and redtrieve 
	 * its rappresenting schema if have it
	 * 
	 * @param type $notation
	 * @return type
	 */
    public static function getSchema($notation)
    {
        ##
        return json_decode(trim($notation,'<>'),true);
    }

    ## handle creation of related object
    public static function object_build($d,$a,&$r)
    {
        ##
        $t = schemadb::get_type($d);

        ##
        switch ($t) {
            case 'class':
                $c = schemadb::get_class($d);
                $o = new $c();
                $o->fill($a);
                $o->store();
                $k = $o::primary_key();
                $r = $o->{$k};
                break;
        }
    }

    ## printout database status/info
    public static function parse_date($date)
    {
        ##
        if ($date != '0000-00-00') {
            return @date('Y-m-d',@strtotime(''.$date));
        } else {
            return null;
        }
    }

    ## printout database status/info
    public static function parse_datetime($datetime)
    {
        if ($datetime!='0000-00-00 00:00:00') {
            return @date('Y-m-d H:i:s',@strtotime(''.$datetime));
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

