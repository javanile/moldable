<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Functions;

trait ManageApi 
{
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
}
