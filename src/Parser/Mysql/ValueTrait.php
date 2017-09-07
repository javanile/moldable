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
     *
     * @return type
     */
    public function getNotationValue($notation)
    {
        $type = $this->getNotationType($notation, $params);
        $value = null;

        switch ($type) {
            case 'integer':
                return (int) $notation;
            case 'boolean':
                return (bool) $notation;
            case 'primary_key':
                return;
            case 'string':
                return (string) $notation;
            case 'text':
                $value = $this->getNotationValueString($notation);
                break;
            case 'float':
                return (float) $notation;
            case 'double':
                return (float) $notation;
            case 'class':
                return;
            case 'vector':
                return;
            case 'matchs':
                return;
            case 'array':
                return;
            case 'enum':
                return !is_string($notation) && isset($notation[0]) && !is_null($notation[0]) ? $notation[0] : null;
            case 'time':
                return static::parseTime($notation);
            case 'date':
                return static::parseDate($notation);
            case 'datetime':
                return static::parseDatetime($notation);
            case 'timestamp':
                return;
            case 'schema':
                return;
            case 'column':
                return;
            case 'json':
                return;
            case 'null':
                return;
            default:
                trigger_error("No PSEUDOTYPE value for '{$type}' => '{$notation}'", E_USER_ERROR);
        }
    }

    protected function getNotationValueString($notation)
    {
        if (preg_match('/<<@[a-z_]+>>/', $notation)) {
            return '';
        }

        return $notation;
    }
}
