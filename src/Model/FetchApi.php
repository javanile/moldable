<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

trait FetchApi
{ 
    /**
     *
     *
     * @param type $array
     */
    protected static function fetch(
        $sql,
        $params = null,
        $singleRecord = false,
        $singleValue = false,
        $casting = true
    ) {
        // requested a single record
        if ($singleRecord && !$singleValue && $casting) {

            //
            $record = static::getDatabase()->getRow($sql, $params);

            //
            return $record ? static::make($record): null;
        }

        // requested a multiple records many value per records
        else if (!$singleRecord && !$singleValue) {

            //
            $records = static::getDatabase()->getResults($sql, $params);

            //
            if (!$records) { return; }

            //
            if ($casting) {
                foreach($records as &$record) {
                    $record = static::make($record);
                }
            }

            //
            return $records;
        }

        // requested a single value of a single record
        else if ($singleRecord && $singleValue) {

            //
            $value = static::getDatabase()->getValue($sql, $params);

            //
            return $value;
        }
    }
}
