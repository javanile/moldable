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
    private function getNotationAspectsBoolean(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'tinyint(1)';
        $aspects['Default'] = (int) $notation;
        $aspects['Null'] = 'NO';

        return $aspects;
    }

    private function getNotationAspectsInteger(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'int(11)';
        $aspects['Default'] = (int) $notation;
        $aspects['Null'] = 'NO';

        return $aspects;
    }

    private function getNotationAspectsFloat(
        $notation,
        $aspects
    ) {
        $aspects['Null'] = 'NO';
        $aspects['Type'] = 'float(12,2)';
        $aspects['Default'] = (float) $notation;

        return $aspects;
    }

    private function getNotationAspectsDouble(
        $notation,
        $aspects
    ) {
        $aspects['Null'] = 'NO';
        $aspects['Type'] = 'double(10,4)';
        $aspects['Default'] = (float) $notation;

        return $aspects;
    }
}
