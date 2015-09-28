<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * static part of sdbClass
 *
 *
 */
class Table 
{
    ## schemadb mysql constants for rapid fields creation
    const PRIMARY_KEY	= '<{"Key":"PRI","Extra":"auto_increment"}>';
    const VARCHAR		= '<{"Type:varchar(255)}>';
    const VARCHAR_80	= '<{"Type:varchar(80)}>';
    const VARCHAR_255	= '<{"Type:varchar(255)}>';
    const TEXT			= '<{"Type":"text"}>';
    const INT			= '<{"Type:int(10)}>';
    const INT_10		= '<{"Type:int(10)}>';
    const INT_14		= '<{"Type:int(14)}>';
    const FLOAT			= '<{"Type:float(14,4)}>';
    const FLOAT_14_4	= '<{"Type:float(14,4)}>';
    const TIME			= '00:00:00';
    const DATE			= '0000-00-00';
    const DATETIME		= '0000-00-00 00:00:00';
    
    /**
	 * Retrieve table name
	 * 
	 * @return string
	 */
    public static function getTable()
    {        
        ## retrieve value from class setting definition
		if (static::hasClassSetting('table')) {
			return static::getClassSetting('table');
		}
		
		## 
		else if (isset(static::$table)) {
            $name = static::$table;
        } 
		
		##
		elseif (isset(static::$class)) {
            $name = static::$class;
        } 
		
		##
		else {
            $name = get_called_class();
        }

		## get prefix
        $table = static::getDatabase()->getPrefix() . $name;

		## store as setting for future request
		static::setClassSetting('table', $table);
								
        ## return complete table name
        return $table;
    }

    ##
    public static function getDatabase()
    {
        ##
        return isset(static::$SchemaDB) ? static::$SchemaDB : Database::getDefault();
    }

    ##
    public static function make($data=null)
    {
        ##
        $o = new static();

        ##
        if ($data) {
            $o->fill($data);
        }

        ##

        return $o;
    }

    ##
    public static function build($data=null)
    {
        ##

        return static::make($data);
    }

    ##
    public static function all()
    {
        ##
        static::updateTable();

        ##
        $t = static::getTable();

        ##
        $q = "SELECT * FROM {$t}";

        ##
        $r = static::getDatabase()->getResults($q);

        ##
        $a = array();

        ##
        foreach ($r as $i=>$o) {
            $a[$i] = static::make($o);
        }

        ##

        return $a;
    }

    ##
    public static function query($query)
    {
        ##
        $x = $query;

        ##
        $t = self::getTable();

        ## where block for the query
        $h = array();

        ##
        if (isset($x['where'])) {
            $h[] = "(".$x['where'].")";
        }

        ##
        foreach ($x as $k=>$v) {

            ##
            if (in_array($k,array('order','where','limit'))) {
                continue;
            }

            ##
            $h[] = "{$k} = '{$v}'";
        }

        ##
        $w = count($h) > 0 ? 'WHERE '.implode(' AND ',$h) : '';

        ## order by block
        $o = isset($x['order']) ? 'ORDER BY '.$x['order'] : '';

        ## order by block
        $l = isset($x['limit']) ? 'LIMIT '.$x['limit'] : '';

        ## build query
        $q = "SELECT * FROM {$t} {$w} {$o} {$l}";

        ## fetch res
        $r = static::getDatabase()->getResults($q);

        ##
        foreach ($r as &$i) {
            $i = static::make($i);
        }

        ##

        return $r;
    }

    ##
    public static function first()
    {
        ##
        $t = static::getTable();

        ##
        $s = "SELECT * FROM {$t} LIMIT 1";

        ##
        $r = schemadb::execute('row',$s);

        ##
        if ($r) {
            return static::build($r);
        }

    }

    ## alias 6char of ping
    public static function exists($query)
    {
        return static::ping($query);
    }

    ##
    public static function ping($query)
    {
        ##
        static::updateTable();

        ##
        $t = self::getTable();
        $w = array();

        ##
        if (isset($query['where'])) {
            $w[] = $query['where'];
            unset($query['where']);
        }

        ##
        foreach (static::getSchema() as $f=>$d) {
            if (isset($query[$f])) {
                $v = $query[$f];
                $w[] = "{$f}='$v'";
            }
        }

        ##
        $w = count($w)>0 ? 'WHERE '.implode(' AND ',$w) : '';

        ##
        $s = "SELECT * FROM {$t} {$w} LIMIT 1";

        ##
        $r = schemadb::execute('row',$s);

        ##
        if ($r) {
            return self::build($r);
        }
    }

    ##
    public static function submit($query)
    {
        ##
        $o = static::ping($query);

        ##
        if (!$o) {
            $o = static::build($query);
            $o->store();
        }

        ##

        return $o;
    }

    ##
    public static function insert($query)
    {
        ##
        $o = static::build($query);
        $o->store_insert();

        ##

        return $o;
    }

    /**
     *
     * @param  type $query
     * @param  type $values
     * @return type
     */
    public static function update($query, $values)
    {
        ##
        $o = static::build($query);
        $o->store_update();

        ##

        return $o;
    }

    /**
     * Import records from a source
     *
     * @param type $source
     */
    public static function import($source)
    {
        ## source is array loop throut records
        foreach ($source as $record) {

            ## insert single record
            static::insert($record);
        }
    }

    /**
     * Encode/manipulate field on object
     * based on encode_ static method of class
     *
     * @param  type $object
     * @return type
     */
    public static function encode($object)
    {
        ##
        $c = static::getClass();

        ##
        foreach ($object as $f=>$v) {

            ##
            $m = 'encode_'.$f;

            ##
            if (!method_exists($c,$m)) { continue; }

            ##
            else if (is_object($object)) {
                $data->{$f} = call_user_func($c.'::'.$m,$v);
            }

            ##
            else if (is_array($object)) {
                $data[$f] = call_user_func($c.'::'.$m,$v);
            }
        }

        ##

        return $object;
    }

    ##
    public static function decode($data)
    {
        ##
        $c = get_called_class();

        ##
        foreach ($data as $f=>$v) {
            $m = 'decode_'.$f;
            if (method_exists($c,$m)) {
                if (is_object($data)) {
                    $data->{$f} = call_user_func($c.'::'.$m,$v);
                } else {
                    $data[$f] = call_user_func($c.'::'.$m,$v);
                }
            }
        }

        ##

        return $data;
    }

    ##
    public static function map($data,$map)
    {
        ##
        $o = static::make($data);

        ##
        foreach ($map as $m=>$f) {
            $o->{$f} = isset($data[$m]) ? $data[$m] : '';
        }

        ##

        return $o;
    }

    ##
    public static function dump($list=null)
    {
        ##
        $a = $list ? $list : static::all();

        ##
        $t = static::getTable();

        ##
        $r = key($a);

        ##
        $n = count($a) > 0 ? count((array) $a[$r]) : 1;

        ##
        echo '<pre><table border="1" style="text-align:center"><thead><tr><th colspan="'.$n.'">'.$t.'</th></tr>';

        ##
        echo '<tr>';
        foreach ($a[$r] as $f=>$v) {
            echo '<th>'.$f.'</th>';
        }
        echo '</tr></thead><tbody>';

        ##
        foreach ($a as $i=>$r) {
            echo '<tr>';
            foreach ($r as $f=>$v) {
                echo '<td>'.$v.'</td>';
            }
            echo '</tr>';
        }

        ##
        echo '</tbody></table></pre>';
    }

    ##
    public static function desc()
    {
        ##
        $t = static::getTable();

        ##
        $s = static::getSchemaDB()->desc_table($t);

        ##
        echo '<table border="1" style="text-align:center"><tr><th colspan="8">'.$t.'</td></th>';

        ##
        $d = reset($s);

        ##
        echo '<tr>';
        foreach ($d as $a=>$v) {
            echo '<th>'.$a.'</th>';
        }
        echo '</tr>';

        ##
        foreach ($s as $d) {
            echo '<tr>';
            foreach ($d as $a=>$v) {
                echo '<td>'.$v.'</td>';
            }
            echo '</tr>';
        }

        ##
        echo '</table>';
    }

    /**
	 * Instrospect and retrieve element schema
	 *  
	 * @return type
	 */
    public static function getSchema()
    {		
		##
		if (static::hasModelSetting('schema')) {
			return static::getModelSetting('schema');
		}
		
		##
        $class = static::getClass();

        ##
        $vars = get_class_vars($class);

        ##
        $schema = array();

        ##
        foreach ($vars as $name => $value) {
            if (!in_array($name, static::getModelSetting('exclude'))) {
                $schema[$name] = $value;
            }
        }

		##
		static::setModelSetting('schema', $schema);
		
        ##
        return $schema;
    }

    /**
	 * 
	 * @return type
	 */ 
    public static function updateTable()
    {        
        ## avoid re-update by check the cache
        if (static::hasModelSetting('update')) {	
			return;
		}

        ## get table name
        $table = static::getTable();

        ## and model schema
        $schema = Parser::parseSchemaTable(static::getSchema());

        ## have a valid schema update db table
        if (count($schema) > 0) {
            static::getDatabase()->updateTable($table, $schema, false);
        }

        ## cache last update avoid multiple call
        static::setModelSetting('update', time());
    }

    ##
    public static function connect($conn=null)
    {
        ##
        if (schemadb::connected()) {

            ##
            static::updateTable();
        }
    }

    ## usefull mysql func
    public static function now()
    {
        ##

        return @date('Y-m-d H:i:s');
    }

    /**
     *
     *
     * @param type $array
     */
    protected static function fetch($sql,$array=false,$value=false)
    {
        ##
        if (!$value) {
            return static::make(static::getSchemaDB()->getRow($sql));
        }

        ##
        else {
            return static::getSchemaDB()->getValue($sql);
        }
    }
}
