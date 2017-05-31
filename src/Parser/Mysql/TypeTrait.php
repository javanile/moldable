<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait TypeTrait
{
    /**
     * Get type of a notation
     *
     * @param type $notation
     * @param type $params
     * @return string
     */
    public static function getNotationType($notation, &$params = null)
    {
        $type = gettype($notation);
        $params = null;

        switch ($type) {
            case 'string':
                return static::getNotationTypeString($notation, $params);
            case 'array':
                return static::getNotationTypeArray($notation);
            case 'integer':
                return 'integer';
            case 'double':
                return 'float';
            case 'boolean':
                return 'boolean';
            case 'NULL':
                return 'null';
        }
    }

    /**
     *
     * @param type $notation
     */
    private static function getNotationTypeString($notation, &$params)
    {
        //
        $matchs = null;

        //
        $params = null;

        //
        if (preg_match('/^<<#([a-z_]+)>>$/i', $notation, $matchs)) {
            return $matchs[1];
        }

        //
        else if (preg_match('/^<<primary key ([1-9][0-9]*)>>$/', $notation, $matchs)) {
            $params = array_slice($matchs, 1);
            return 'primary_key';
        }

        //
        else if (static::pregMatchClass($notation, $matchs)) {
            $params[0] = $matchs[1];
            return 'class';
        }

        //
        else if (static::pregMatchVector($notation, $matchs)) {
            return 'vector';
        }

        //
        else if (static::pregMatchMatchs($notation, $matchs)) {
            return 'matchs';
        }

        //
        else if (preg_match('/^<<\{.*\}>>$/si', $notation)) {
            return 'json';
        }

        //
        else if (preg_match('/^<<\[.*\]>>$/si', $notation)) {
            return 'enum';
        }

        //
        else if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/', $notation)) {
            return 'datetime';
        }

        //
        else if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/', $notation)) {
            return 'date';
        }

        //
        else if (preg_match('/^[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/', $notation)) {
            return 'time';
        }

        //
        else {
            return 'string';
        }
    }

    /**
     *
     *
     * @param type $notation
     * @return string
     */
    private static function getNotationTypeArray(&$notation)
    {
        if ($notation && $notation == array_values($notation)) {
            return 'enum';
        }

        return 'schema';
    }
}
