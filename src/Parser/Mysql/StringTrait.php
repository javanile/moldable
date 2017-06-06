<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait StringTrait
{
    /**
     *
     */
    private function getNotationAspectsString($notation, $aspects)
    {
        //
        $aspects['Type'] = 'varchar(255)';
        $aspects['Null'] = 'NO';
        $aspects['Default'] = $this->getNotationValue($notation);

        return $aspects;
    }

    /**
     *
     */
    private function getNotationAspectsText($notation, $aspects)
    {
        $aspects['Type'] = 'text';
        $aspects['Null'] = 'NO';
        $aspects['Default'] = $this->getNotationValue($notation);

        return $aspects;
    }

    /**
     *
     *
     */
    private function getNotationAspectsNull(
        $notation,
        $aspects
    ) {
        $aspects['Type'] = 'varchar(255)';
        $aspects['Default'] = $notation;

        return $aspects;
    }
}
