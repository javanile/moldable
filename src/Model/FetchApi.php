<?php
/**
 * Trait to fetch database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait FetchApi
{
    /**
     * Fetch data from db.
     *
     * @param mixed      $sql
     * @param null|mixed $params
     * @param mixed      $singleRecord
     * @param mixed      $singleValue
     * @param mixed      $casting
     *
     * @return null|void
     *
     * @internal param type $array
     */
    protected static function fetch(
        $sql,
        $params = null,
        $singleRecord = false,
        $singleValue = false,
        $casting = true
    ) {
        $results = null;

        try {
            $results = static::unsafeFetch($sql, $params, $singleRecord, $singleValue, $casting);
        } catch (DatabaseException $ex) {
            static::error(debug_backtrace(), $ex);
        }

        return $results;
    }

    /**
     * Fetch data from db.
     *
     * @param mixed      $sql
     * @param null|mixed $params
     * @param mixed      $singleRecord
     * @param mixed      $singleValue
     * @param mixed      $casting
     *
     * @internal param type $array
     */
    protected static function unsafeFetch(
        $sql,
        $params = null,
        $singleRecord = false,
        $singleValue = false,
        $casting = true
    ) {
        /*
        \Javanile\Producer::log([
            'singleRecord' => $singleRecord,
            'singleValue' => $singleValue,
            'casting' => $casting,
        ]);
        */

        // requested a single record
        if ($singleRecord && !$singleValue) {
            $record = static::getDatabase()->getRow($sql, $params);

            return $record ? ($casting ? static::create($record) : $record) : null;
        }

        if (!$singleRecord && !$singleValue) {
            // requested a multiple records many value per records
            $records = static::getDatabase()->getResults($sql, $params);

            if (!$records) {
                return;
            }

            //
            if ($casting) {
                foreach ($records as &$record) {
                    $record = static::create($record);
                }
            }

            return $records;
        } elseif ($singleRecord && $singleValue) {
            // requested a single value of a single record
            $value = static::getDatabase()->getValue($sql, $params);

            return $value;
        }
    }
}
