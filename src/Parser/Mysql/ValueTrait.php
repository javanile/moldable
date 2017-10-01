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

        if (in_array($type, static::TYPE_WITHOUT_VALUE)) {
            return;
        }

        switch ($type) {
            case 'integer':
                return (int) $notation;
            case 'boolean':
                return (bool) $notation;
            case 'string':
                return (string) $notation;
            case 'float':
                return (float) $notation;
            case 'double':
                return (float) $notation;
            case 'enum':
                return $this->parseEnum($notation);
            case 'time':
                return static::parseTime($notation);
            case 'date':
                return static::parseDate($notation);
            case 'datetime':
                return static::parseDatetime($notation);
        }

        // called if detected type not is handled
        trigger_error("No PSEUDOTYPE value for '{$type}' => '{$notation}'", E_USER_ERROR);
    }

    /**
     * Strip notation if type string and get default value.
     */
    protected function getNotationValueString($notation)
    {
        if (preg_match('/<<@[a-z_]+>>/', $notation)) {
            return '';
        }

        return $notation;
    }

    /**
     * Parse Enum to get a default value.
     */
    protected function parseEnum($notation)
    {
        return !is_string($notation)
            && isset($notation[0])
            && !is_null($notation[0])
            ? $notation[0] : null;
    }
}
