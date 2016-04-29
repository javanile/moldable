<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

trait FetchApi
{
    /**
     *
     *
     * @param type $array
     */
    protected static function fetch(
        $sql,
        $params=null,
        $singleRecord=false,
        $singleValue=false,
        $casting=true
    ) {
        // requested a single record
        if ($singleRecord && !$singleValue && $casting) {

            //
            $record = static::getDatabase()->getRow($sql, $params);

            //
            return $record ? static::make($record): null;
        }

        // requested a single record
        else if (!$singleRecord && !$singleValue && $casting) {

            //
            $records = static::getDatabase()->getResults($sql, $params);

            //
            if (!$records) {
                return;
            }

            //
            foreach($records as &$record) {
                $record = static::make($record);
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
