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
    private function getNotationAspectsEnum($notation, $aspects)
    {
        //
        $enum = static::parseNotationEnum($notation);
        if (!$enum) {
            return static::getNotationAttributes('', $field, $before);
        }

        //
        //$aspects['Enum'] = $enum;
        $aspects['Default'] = $enum[0];
        $aspects['Null'] = in_array(null, $enum) ? 'YES' : 'NO';

        //
        $tokens = [];
        foreach ($enum as $item) {
            if ($item !== null) {
                $tokens[] = "'{$item}'";
            }
        }
        $aspects['Type'] = 'enum('.implode(',', $tokens).')';

        return $aspects;
    }

    private static function parseNotationEnum($notation)
    {
        if (is_string($notation)) {
            $notation = json_decode(trim($notation, '<>'));

            if (json_last_error()) {
                return;
            }
        }

        return $notation;
    }
}
