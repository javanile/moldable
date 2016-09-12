<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Functions;

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
            $object->fillSchemaFields($values, $map, $prefix);
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

    
}