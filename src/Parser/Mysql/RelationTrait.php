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
    private function getNotationAspectsVector($notation, $aspects, $params)
    {
        $aspects['Relation'] = '1:*';

        return $aspects;
    }

    /**
     *
     */
    private static function getNotationAspectsMatchs($notation, $aspects, $params)
    {
        $aspects['Relation'] = '*:*';

        return $aspects;
    }

    /**
     *
     */
    public static function isClass($notation, &$matchs)
    {
        return preg_match(
            '/^<<[ \t]*'.static::REGEX_PHP_CLASS.'[ \t]*>>$/',
            $notation,
            $matchs
        );
    }

    /**
     *
     */
    public static function pregMatchVector($notation, &$matchs)
    {
        //
        return preg_match(
            '/^<<'.static::REGEX_PHP_CLASS.'\*>>$/',
            $notation,
            $matchs
        );
    }

    /**
     *
     */
    public static function pregMatchMatchs($notation, &$matchs)
    {
        return preg_match(
            '/^<<'.static::REGEX_PHP_CLASS.'\*\*>>$/',
            $notation,
            $matchs
        );
    }
}
