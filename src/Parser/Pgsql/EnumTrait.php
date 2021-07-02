<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser\Pgsql;

trait EnumTrait
{
    /**
     * Get notation aspects for enum.
     *
     * @param mixed $notation
     * @param mixed $aspects
     */
    protected function getNotationAspectsEnum($notation, $aspects)
    {
        //
        $enum = $this->parseEnumNotation($notation);
        if (!$enum) {
            return $aspects;
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

    /**
     * Parse enum if is inside a string.
     *
     * @param mixed $notation
     */
    private function parseEnumNotation($notation)
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
