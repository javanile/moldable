<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait Common
{
    /**
     *
     *
     */
    private static function getNotationAttributesJson(
        $notation,
        $field,
        $before
    ) {
        // decode json object into notation
        $json = json_decode(trim($notation,'<>'), true);

        // set with default attributes
        $attr = static::getNotationAttributesCommon($field, $before);

        // override default with json passed
        foreach ($json as $key => $value) {
            $attr[$key] = $value;
        }

        //
        return $attr;
    }

    /**
     *
     *
     */
    private static function
    getNotationAttributesSchema($notation, $field, $before)
    {
        // set with default attributes
        $attr = static::getNotationAttributesCommon($field, $before);

        // override default notation schema passed
        foreach ($notation as $key => $value) {
            $attr[$key] = $value;
        }

        //
        return $attr;
    }

    /**
     *
     *
     */
    private static function getNotationAttributesNull(
        $notation,
        $field,
        $before
    ) {
        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Type'] = 'varchar(255)';

        //
        $aspects['Default'] = $notation;

        //
        return $aspects;
    }
}