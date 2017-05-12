<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait Relation
{
    /**
     *
     *
     */
    private static function
    getNotationAttributesClass($notation, $field, $before, $params)
    {
        //
        $attributes = static::getNotationAttributesCommon($field, $before);

        //
        $attributes['Type'] = 'int(11)';

        //
        $attributes['Class'] = $params[0];

        //
        $attributes['Relation']    = '1:1';

        //
        return $attributes;
    }

    /**
     *
     *
     */
    private static function
    getNotationAttributesVector($notation, $field, $before, $params)
    {
        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Relation'] = '1:*';

        //
        return $aspects;
    }

    /**
     *
     *
     */
    private static function
    getNotationAttributesMatchs($notation, $field, $before, $params)
    {
        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Relation'] = '*:*';

        //
        return $aspects;
    }

    /**
     *
     *
     */
    public static function pregMatchClass($notation, &$matchs)
    {
        //
        return preg_match(
            '/^<<'.static::CLASSREGEX.'>>$/',
            $notation,
            $matchs
        );
    }

    /**
     *
     *
     */
    public static function pregMatchVector($notation, &$matchs)
    {
        //
        return preg_match(
            '/^<<'.static::CLASSREGEX.'\*>>$/',
            $notation,
            $matchs
        );
    }

    /**
     *
     *
     */
    public static function pregMatchMatchs($notation, &$matchs)
    {
        //
        return preg_match(
            '/^<<'.static::CLASSREGEX.'\*\*>>$/',
            $notation,
            $matchs
        );
    }
}