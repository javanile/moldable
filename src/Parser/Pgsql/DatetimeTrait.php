<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser\Mysql;

trait DatetimeTrait
{
    private function getNotationAspectsDate(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'date';
        $aspects['Default'] = $notation;

        return $aspects;
    }

    private function getNotationAspectsTime(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'time';
        $aspects['Default'] = $notation;

        return $aspects;
    }

    private function getNotationAspectsDatetime(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'datetime';
        $aspects['Default'] = $notation;

        return $aspects;
    }

    /**
     * @param mixed $notation
     * @param mixed $aspects
     */
    private static function getNotationAspectsTimestamp(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'timestamp';
        $aspects['Null'] = 'NO';
        $aspects['Default'] = 'CURRENT_TIMESTAMP';

        return $aspects;
    }

    /**
     * printout database status/info.
     *
     * @param mixed $date
     * @param mixed $time
     */
    public static function parseTime($time)
    {
        //
        if ($time != '00:00:00') {
            return @date('H:i:s', @strtotime(''.$time));
        } else {
            return;
        }
    }

    /**
     * printout database status/info.
     *
     * @param mixed $date
     */
    public static function parseDate($date)
    {
        //
        if ($date != '0000-00-00') {
            return @date('Y-m-d', @strtotime(''.$date));
        } else {
            return;
        }
    }

    /**
     * printout database status/info.
     *
     * @param mixed $datetime
     */
    public static function parseDatetime($datetime)
    {
        if ($datetime != '0000-00-00 00:00:00') {
            return @date('Y-m-d H:i:s', @strtotime(''.$datetime));
        } else {
            return;
        }
    }
}
