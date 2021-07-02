<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser\Pgsql;

trait StringTrait
{
    /**
     * Get notaion aspect for string.
     *
     * @param mixed $notation
     * @param mixed $aspects
     */
    protected function getNotationAspectsString($notation, $aspects)
    {
        $aspects['Type'] = 'character varying(255)';
        $aspects['Null'] = 'NO';
        $aspects['Default'] = $this->getNotationValue($notation);

        return $aspects;
    }

    protected function getNotationAspectsText($notation, $aspects)
    {
        $aspects['Type'] = 'text';
        $aspects['Null'] = 'YES';
        $aspects['Default'] = $this->getNotationValue($notation);

        return $aspects;
    }

    protected function getNotationAspectsNull(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'character varying(255)';
        $aspects['Default'] = $notation;

        return $aspects;
    }
}
