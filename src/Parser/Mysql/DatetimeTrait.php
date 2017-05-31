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
    /**
     *
     *
     */
    private static function getNotationAttributesDate(
        $notation,
        $field,
        $before
    ) {
        $attributes = static::getNotationAttributesCommon($field, $before);
        $attributes['Type'] = 'date';
        $attributes['Default'] = $notation;

        return $attributes;
    }

    /**
     *
     *
     */
    private static function getNotationAttributesTime(
        $notation,
        $field,
        $before
    ) {
        $attributes = static::getNotationAttributesCommon($field, $before);
        $attributes['Type'] = 'time';
        $attributes['Default'] = $notation;

        return $attributes;
    }

    /**
     *
     *
     */
    private static function
    getNotationAttributesDatetime($notation, $field, $before)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

        //
        $attributes['Type'] = 'datetime';

        //
        $attributes['Default'] = $notation;

        //
        return $attributes;
    }

    /**
     *
     */
    private static function
    getNotationAttributesTimestamp($notation, $field, $before)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

        //
        $attributes['Type'] = 'timestamp';

        //
        $attributes['Null'] = 'NO';

        //
        $attributes['Default'] = 'CURRENT_TIMESTAMP';

        //
        return $attributes;
    }

    // printout database status/info
    public static function parseDate($date)
    {
        //
        if ($date != '0000-00-00') {
            return @date('Y-m-d', @strtotime('' . $date));
        } else {
            return null;
        }
    }

    // printout database status/info
    public static function parseDatetime($datetime)
    {
        if ($datetime != '0000-00-00 00:00:00') {
            return @date('Y-m-d H:i:s', @strtotime('' . $datetime));
        } else {
            return null;
        }
    }
}
