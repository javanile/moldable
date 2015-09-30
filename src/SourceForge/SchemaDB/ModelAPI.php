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
class ModelAPI extends Model {
	
	/**
     * Load item from DB by primary key
     *
     * @param  type $id
     * @return type
     */
    public static function load($id,$fields=null)
    {
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

    /**
	 * Alias of ping
	 * 
	 * @param type $query
	 * @return type
	 */
    public static function exists($query)
    {
		##
		return static::ping($query);
    }

    /**
	 * 
	 * 
	 * @param type $query
	 * @return type
	 */
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

    /**
	 * 
	 * 
	 * @param type $query
	 * @return type
	 */
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
    public static function insert($values)
    {
        ##
        $o = static::make($values);
        
		##
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
     * Encode/manipulate field on object
     * based on encode_ static method of class
     *
     * @param  type $$values
     * @return type
     */
    public static function encode($values)
    {
        ##
        $class = static::getClass();

        ##
        foreach ($values as $field => $value) {

            ##
            $method = 'encode_'.$field;

            ##
            if (!method_exists($class,$method)) { continue; }

            ##
            else if (is_object($values)) {
                $values->{$f} = call_user_func($class.'::'.$method,$value);
            }

            ##
            else if (is_array($$values)) {
                $values[$f] = call_user_func($c.'::'.$m,$v);
            }
        }

        ##
        return $values;
    }

    ##
    public static function decode($values)
    {
		##
		$object = is_array($values) ? static::make($values) : $values;
		
		##
		if (!is_object($values)) { return $object;}
        
		
		var_dump($object);
		die();
		
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

    /**
     * Delete element by primary key or query
     *
     * @param type $query
     */
    public static function delete($query)
    {
        ##
        $t = static::getTable();

        ##
        if (is_array($query)) {

            ## where block for the query
            $h = array();

            ##
            if (isset($query['where'])) {
                $h[] = $query['where'];
            }

            ##
            foreach ($query as $k=>$v) {
                if ($k!='sort'&&$k!='where') {
                    $h[] = "{$k}='{$v}'";
                }
            }

            ##
            $w = count($h)>0 ? 'WHERE '.implode(' AND ',$h) : '';

            ##
            $s = "DELETE FROM {$t} {$w}";

            ## execute query
            static::getSchemaDB()->query($s);
        }

        ##
        else if ($query > 0) {

            ## prepare sql query
            $k = static::getPrimaryKey();

            ##
            $i = (int) $query;

            ##
            $q = "DELETE FROM {$t} WHERE {$k}='{$i}' LIMIT 1";

            ## execute query
            static::getDatabase()->query($q);
        }
    }
	
	
}