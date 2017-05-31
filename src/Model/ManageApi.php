<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

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
     * Insert persistent object in db and return it.
     *
     * @param type $values
     * @return type
     */
    public static function insert($values)
    {
        // Make object and insert into DB
        $object = static::make($values);
        $object->storeInsert();

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
        static::applySchema();

        //
        $table = static::getTable();

        //
        if (is_array($query)) {
            $whereArray = array();

            //
            if (isset($query['where'])) {
                $whereArray[] = $query['where'];
            }

            //
            foreach ($query as $k=>$v) {
                if ($k!='sort'&&$k!='where') {
                    $whereArray[] = "{$k}='{$v}'";
                }
            }

            //
            $where = count($h)>0 ? 'WHERE '.implode(' AND ', $whereArray) : '';
            $sql = "DELETE FROM {$table} {$where}";

            // execute query
            static::getDatabase()->execute($sql);
        }

        //
        else if ($query > 0) {
            $key = static::getPrimaryKey();

            //
            $index = (int) $query;

            //
            $sql = "DELETE FROM {$table} WHERE {$key}='{$index}' LIMIT 1";

            // execute query
            static::getDatabase()->execute($sql);
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
        $object = static::exists($query);

        if (!$object) {
            $object = static::make($query);
            $object->store();
        }

        return $object;
    }
}
