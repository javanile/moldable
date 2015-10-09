<?php

/*\
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 * 
 * 
 */
class ModelPublicAPI extends ModelProtectedAPI 
{	
	/**
	 * 
	 * @param type $database
	 */
    public static function connect($database=null)
    {
		##
		static::updateTable();       
    }

	/**
	 * 
	 * @param type $values
	 * @param type $map
	 * @return \static
	 */
    public static function make($values=null, $map=null)
    {
        ##
        $object = new static();

        ##
        if ($values) {
            $object->fill($values);
        }

        ##
        return $object;
    }
	
	/**
     * Load item from DB
     *
     * @param  type $id
     * @return type
     */
    public static function load($index, $fields=null)
    {
		## 
		if (is_array($index)) {
			return static::loadByQuery($index, $fields); 
		}

		##
		$key = static::getPrimaryKey();
		
		## 
		if ($key) {			
			return static::loadByPrimaryKey($index, $fields); 			
		} 
		
		## 
		else {
			return static::loadByMainField($index, $fields); 								
		}
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

	/**
	 * 
	 * 
	 * @param type $fields
	 * @return type
	 */
    public static function all($fields=null)
    {
        ##
        static::updateTable();

        ##
        $table = static::getTable();

		##
		$join = "";
		
		##
		$class = get_called_class();
		
		## 
		$selectFields = MysqlComposer::selectFields($fields, $class, $join);
		
        ##
        $sql = "SELECT {$selectFields} FROM {$table} AS {$class} {$join}";

		##
		$results = static::fetch($sql, true);
		
		##
        return $results;
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
        $r = static::getDatabase()->getRow($s);

        ##
        if ($r) {
            return self::make($r);
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
        $object = static::make($values);
        
		##
		$object->storeInsert();

        ##
        return $object;
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
        $o->storeUpdate();

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
    public static function encode($values, $map=null)
    {
		##
		return static::filter($value, 'decode_', $map);		
    }

    /**
	 * 
	 * 
	 * @param type $values
	 * @return type
	 */
    public static function decode($values, $map=null)
    {					
		##
		return static::filter($value, 'decode_', $map);		
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
	
	/**
	 * 
	 */
	public static function join($fieldFrom, $fieldTo = null) {
		
		##
		if (!is_string($fieldFrom)) {
			trigger_error('Required field to join', E_USER_ERROR);
		}
		
		##
		return array(
			'Table'		=> static::getTable(),			
			'Class'		=> static::getClass(),
			'FieldFrom'	=> $fieldFrom,		
			'FieldTo'	=> $fieldTo ? $fieldTo : static::getMainField(),
			'JoinKey'	=> static::getPrimaryKey(),
		);		
	} 
	
	/**
	 * Drop table
	 * 
	 * @param type $confirm
	 * @return type
	 */ 
    public static function drop($confirm=null)
    {
        ##
        if ($confirm !== 'confirm') {
            return;
        }

        ## prepare sql query
        $t = static::getTable();

        ##
        $q = "DROP TABLE IF EXISTS {$t}";

		##
		static::delConfig('update');
		
        ## execute query
        static::getDatabase()->query($q);
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
	 * 
	 * @param type $list
	 */
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

	/**
	 * 
	 */
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

	
	
}