<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait ValueTrait
{
    /**
     * Retrieve value of a parsable notation
     * Value rapresent ...
     *
     * @param type $notation
     * @return type
     */
    public function getNotationValue($notation)
    {
        $type = static::getNotationType($notation, $params);

        switch ($type) {
            case 'integer':
                return (int) $notation;
            case 'boolean':
                return (boolean) $notation;
            case 'primary_key':
                return null;
            case 'string':
                return (string)$notation;
            case 'float':
                return (float)$notation;
            case 'double':
                return (double)$notation;
            case 'class':
                return null;
            case 'vector':
                return null;
            case 'matchs':
                return null;
            case 'array':
                return null;
            case 'enum':
                return !is_string($notation) && isset($notation[0]) && !is_null($notation[0]) ? $notation[0] : null;
            case 'time':
                return static::parseTime($notation);
            case 'date':
                return static::parseDate($notation);
            case 'datetime':
                return static::parseDatetime($notation);
            case 'timestamp':
                return null;
            case 'schema':
                return null;
            case 'column':
                return null;
            case 'json':
                return null;
            case 'null':
                return null;
            default:
                trigger_error("No PSEUDOTYPE value for '{$type}' => '{$notation}'", E_USER_ERROR);
        }
    }
}
