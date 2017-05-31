<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait EnumTrait
{
    /**
     *
     */
    private static function getNotationAttributesEnum(
        $notation,
        $field,
        $before
    ) {
        //
        $enum = static::parseNotationEnum($notation);

        //
        if (!$enum) {
            return static::getNotationAttributes('', $field, $before);
        }

        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Enum'] = $enum;

        //
        $aspects['Default'] = $enum[0];

        //
        $aspects['Null'] = in_array(null, $enum) ? 'YES' : 'NO';

        //
        $t = array();

        //
        foreach ($enum as $i) {
            if ($i !== null) {
                $t[] = "'{$i}'";
            }
        }

        //
        $aspects['Type'] = 'enum('.implode(',',$t).')';

        //
        return $aspects;
    }

    /**
     *
     *
     */
    private static function parseNotationEnum($notation)
    {
        //
        if (is_string($notation)) {

            //
            $notation = json_decode(trim($notation, '<>'));

            //
            if (json_last_error()) { return null; }
        }

        //
        return $notation;
    }



}