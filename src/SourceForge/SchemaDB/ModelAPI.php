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
        $i = (int) $id;

        ##
        $t = static::getTable();

        ## get primary key
        $k = static::getPrimaryKey();

        ## parse SQL select fields
        $f = Mysql::select_fields($fields,$j);

        ## prepare SQL query
        $q = "SELECT {$f} FROM {$t} {$j} WHERE {$k}='{$i}' LIMIT 1";

        ## fetch data on database and return it
        return static::fetch($q, false, is_string($fields));
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