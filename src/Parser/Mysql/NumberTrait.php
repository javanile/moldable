<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait NumberTrait
{
    /**
     *
     *
     */
    private function getNotationAttributesBoolean($notation, $field, $before)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

        //
        $attributes['Type'] = 'tinyint(1)';

        //
        $attributes['Default'] = (int) $notation;

        //
        $attributes['Null'] = 'NO';

        //
        return $attributes;
    }

    /**
     *
     */
    private function getNotationAspectsInteger($notation, $aspects)
    {
        //
        $aspects['Type'] = 'int(11)';
        $aspects['Default'] = (int) $notation;
        $aspects['Null'] = 'NO';

        return $aspects;
    }

    /**
     *
     *
     */
    private static function
    getNotationAttributesFloat($notation, $field, $before)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

        //
        $attributes['Null']    = 'NO';

        //
        $attributes['Type']    = 'float(12,2)';

        //
        $attributes['Default']    = (float) $notation;

        //
        return $attributes;
    }

    /**
     *
     *
     */
    private static function
    getNotationAttributesDouble($notation, $field, $before)
    {
        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Null'] = 'NO';

        //
        $aspects['Type'] = 'double(10,4)';

        //
        $aspects['Default']    = (double) $notation;

        //
        return $aspects;
    }


}