<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait String
{


    /**
     *
     */
    private static function getNotationAttributesString($notation, $field, $before)
    {

//
        $attributes = static::getNotationAttributesCommon($field, $before);

//
        $attributes['Type'] = 'varchar(255)';

//
        $attributes['Default'] = $notation;

//
        $attributes['Null'] = 'NO';

//
        return $attributes;
    }
}

