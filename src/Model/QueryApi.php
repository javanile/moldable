<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\SchemaDB\Model;

trait QueryApi
{
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

    
}
    
    
