<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser\Mysql;

trait RelationTrait
{
    /**
     * Get notation aspects for class.
     */
    private function getNotationAspectsClass($notation, $aspects, $params)
    {
        $aspects['Type'] = 'int(11)';
        $aspects['Class'] = $params['Class'];
        $aspects['Relation'] = '1:1';

        return $aspects;
    }

    /**
     *
     */
    private function getNotationAspectsVector($notation, $aspects, $params, $namespace)
    {
        $aspects['Relation'] = '1:*';

        return $aspects;
    }

    /**
     *
     */
    private static function getNotationAspectsMatchs($notation, $aspects, $params, $namespace)
    {
        $aspects['Relation'] = '*:*';

        return $aspects;
    }
}
