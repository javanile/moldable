<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

trait RawApi
{
    /**
     * Execute a raw query on database.
     *
     * @param type $array
     */
    protected static function raw(
        $sql,
        $params = null,
        $singleRecord = false,
        $singleValue = false,
        $casting = true
    ) {
        // requested a single record
        if ($singleRecord && !$singleValue && $casting) {
            $record = static::getDatabase()->getRow($sql, $params);
            return $record ? static::make($record): null;
        } else if (!$singleRecord && !$singleValue) {
            // requested a multiple records many value per records
            $records = static::getDatabase()->getResults($sql, $params);

            if (!$records) {
                return;
            }

            //
            if ($casting) {
                foreach ($records as &$record) {
                    $record = static::make($record);
                }
            }

            return $records;

        } else if ($singleRecord && $singleValue) {
            // requested a single value of a single record
            $value = static::getDatabase()->getValue($sql, $params);

            return $value;
        }
    }
}
