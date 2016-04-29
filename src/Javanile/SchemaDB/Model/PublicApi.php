<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

trait PublicApi 
{
    /**
     *
     * @param type $database
     */
    public static function connect($database=null)
    {
        //
        static::setDatabase($database);

        //
        static::applyTable();
    }

    /**
     *
     * @param type $values
     * @param type $map
     * @return \static
     */
    public static function make($values=null, $map=null, $prefix=null)
    {
        //
        $object = new static();

        //
        if ($values) {
            $object->fill($values, $map, $prefix);
        }

        //
        return $object;
    }

    /**
     *
     * @param type $query
     * @return type
     */ 
    public static function query($query)
    { 
        //
        static::applyTable(); 

        //
        $x = $query;

        //
        $t = self::getTable();

        // where block for the query
        $h = array();

        //
        if (isset($x['where'])) {
            $h[] = "(".$x['where'].")";
        }

        //
        foreach ($x as $k=>$v) {

            //
            if (in_array($k,array('order','where','limit'))) {
                continue;
            }

            //
            $h[] = "{$k} = '{$v}'";
        }

        //
        $w = count($h) > 0 ? 'WHERE '.implode(' AND ',$h) : '';

        // order by block
        $o = isset($x['order']) ? 'ORDER BY '.$x['order'] : '';

        // order by block
        $l = isset($x['limit']) ? 'LIMIT '.$x['limit'] : '';

        // build query
        $q = "SELECT * FROM {$t} {$w} {$o} {$l}";
       
        // fetch res
        $r = static::getDatabase()->getResults($q);

        //
        foreach ($r as &$i) {
            $i = static::make($i);
        }

        //
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
        //
        static::applyTable();

        //
        if (isset($fields['limit'])) {
            $limit = 'LIMIT '.$fields['limit'];
            unset($fields['limit']);
        } else {
            $limit = '';
        }

        //
        $table = static::getTable();

        //
        $join = '';

        //
        $class = static::getClassName();

        //
        $selectFields = static::getDatabase()
                     -> getWriter()
                     -> selectFields($fields, $class, $join);

        //
        $sql = "SELECT {$selectFields} "
             .   "FROM {$table} AS {$class} "
             .       " {$join} "
             .       " {$limit} ";

        //
        try {
            $results = static::fetch(
                $sql,
                null,
                false,
                is_string($fields),
                is_null($fields)
            );
        }

        //
        catch (DatabaseException $ex) {
            static::error(debug_backtrace(), $ex);
        }

        //
        return $results;
    }

    /**
     *
     * @return type
     */
    public static function first($query=null, $fields=null)
    {
        //
        static::applyTable();

        //
        $table = static::getTable();

        //
        $order = isset($query['order'])
               ? 'ORDER BY '.$query['order']
               : '';
        
        //
        unset($query['order']);

        //
        $whereArray = [];

        //
        if (isset($query['where'])) {
            $whereArray[] = '('.$query['where'].')';
            unset($query['where']);
        }
        
        //
        $valueArray = [];

        //
        if (count($query) > 0) {

            //
            $schema = static::getSchemaFields(); 

            //
            foreach ($schema as $field) {
             
                //
                if (!isset($query[$field])) { continue; }

                //
                $token = ':'.$field;

                //
                $whereArray[] = "`{$field}` = {$token}";

                //
                $valueArray[$token] = $query[$field];
            }
        }

        //
        $where = $whereArray
               ? 'WHERE '.implode(' AND ', $whereArray)
               : '';
        
        //
        $sql = "SELECT * FROM {$table} {$where} {$order} LIMIT 1";

        //
        $result = static::fetch(
            $sql,
            $valueArray,
            true,
            is_string($fields),
            is_null($fields)
        );

        //
        return $result;
    }

    /**
     * Alias of ping
     *
     * @param type $query
     * @return type
     */
    public static function exists($query)
    {
        //
        static::applyTable();

        //
        $table = self::getTable();
        
        //
        $whereArray = [];

        //
        $valuesArray = [];

        //
        if (isset($query['where'])) {
            $whereArray[] = $query['where'];
            unset($query['where']);
        }

        //
        $schema = static::getSchemaFields();

        //
        foreach ($schema as $field) {
            if (isset($query[$field])) {
                $token = ':'.$field;
                $whereArray[] = "`{$field}` = {$token}";
                $valuesArray[$token] = $query[$field];
            }
        }

        //
        $where = count($whereArray)>0
               ? 'WHERE '.implode(' AND ',$whereArray)
               : '';

        //
        $sql = "SELECT * FROM `{$table}` {$where} LIMIT 1";

        //
        $r = static::getDatabase()->getRow($sql, $valuesArray);

        //        
        return $r ? self::make($r) : false;
    }

    /**
     *
     *
     * @param type $query
     * @return type
     */
    public static function submit($query)
    {
        //
        $object = static::exists($query);

        //
        if (!$object) {
            $object = static::make($query);
            $object->store();
        }

        //
        return $object;
    }

    /**
     *
     * @param type $values
     * @return type
     */
    public static function insert($values)
    {
        //
        $object = static::make($values);
        
        //
        $object->storeInsert();

        //
        return $object;
    }
    
    /**
     * Delete element by primary key or query
     *
     * @param type $query
     */
    public static function delete($query)
    {
        //
        static::applyTable();

        //
        $t = static::getTable();

        //
        if (is_array($query)) {

            // where block for the query
            $h = array();

            //
            if (isset($query['where'])) {
                $h[] = $query['where'];
            }

            //
            foreach ($query as $k=>$v) {
                if ($k!='sort'&&$k!='where') {
                    $h[] = "{$k}='{$v}'";
                }
            }

            //
            $w = count($h)>0 ? 'WHERE '.implode(' AND ',$h) : '';

            //
            $s = "DELETE FROM {$t} {$w}";

            // execute query
            static::getDatabase()->execute($s);
        }

        //
        else if ($query > 0) {

            // prepare sql query
            $k = static::getPrimaryKey();

            //
            $i = (int) $query;

            //
            $q = "DELETE FROM {$t} WHERE {$k}='{$i}' LIMIT 1";

            // execute query
            static::getDatabase()->execute($q);
        }
    }

    /**
     *
     *
     */
    public static function ping(&$query)
    {
        //
        $exist = static::exists($query);
        
        //
        $query = $exist ? $exist : static::make($query);

        //
        return $exist;
    }

    /**
     * Encode/manipulate field on object
     * based on encode_ static method of class
     *
     * @param  type $$values
     * @return type
     */
    public function encode()
    {
        // . . .
    }

    /**
     *
     *
     * @param type $values
     * @return type
     */
    public function decode()
    {
        // . . .
    }

    /**
     * Drop table
     *
     * @param type $confirm
     * @return type
     */
    public static function drop($confirm=null)
    {
        //
        if ($confirm !== 'confirm') {
            return;
        }

        // prepare sql query
        $table = static::getTable();

        //
        $sql = "DROP TABLE IF EXISTS `{$table}`";

        // execute query
        static::getDatabase()->execute($sql);

        //
        static::delClassAttribute('ApplyTableExecuted');
    }

    /**
     * Import records from a source
     *
     * @param type $source
     */
    public static function import($source)
    {
        // source is array loop throut records
        foreach ($source as $record) {

            // insert single record
            static::insert($record);
        }
    }

    /**
     *
     * 
     */
    public static function desc()
    {
        //
        $table = static::getTable();

        //
        $desc = static::getDatabase()->descTable($table);

        //
        echo '<table border="1" style="text-align:center"><tr><th colspan="8">'.$table.'</td></th>';

        //
        $attributes = array_keys(reset($desc));

        //
        echo '<tr>';
        foreach ($attributes as $attribute) {
            echo '<th>'.$attribute.'</th>';
        }
        echo '</tr>';

        //
        foreach ($desc as $column) {
            echo '<tr>';
            foreach ($column as $attribute => $value) {
                echo '<td>'.$value.'</td>';
            }
            echo '</tr>';
        }

        //
        echo '</table>';
    }

    /**
     *
     * @param type $list
     */
    public static function dump($list=null)
    {       
        //
        Utils::gridDump(
            static::getTable(),
            $list ? $list : static::all()
        );
    }
}