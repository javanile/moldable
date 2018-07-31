<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait ManageApi
{
    /**
     * Drop table.
     *
     * @param type $confirm
     *
     * @return type
     */
    public static function drop($confirm = null)
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
     * Import records from a source.
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
     *
     * @return type
     */
    public static function insert($values)
    {
        // Make object and insert into DB
        $object = static::create($values);
        $object->storeInsert();

        return $object;
    }

    /**
     * @param type $query
     *
     * @return type
     */
    public static function submit($query)
    {
        $object = static::exists($query);

        if (!$object) {
            $object = static::create($query);
            $object->store();
        }

        return $object;
    }

    /**
     * @param type  $query
     * @param mixed $values
     *
     * @return type
     */
    public static function upsert($query, $values)
    {
        $object = static::exists($query);

        if (!$object) {
            $object = static::create($query);
        }

        $object->store($values);

        return $object;
    }
}
