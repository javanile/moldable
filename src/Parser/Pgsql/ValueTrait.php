<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser\Mysql;

use Javanile\Moldable\Exception;

trait ValueTrait
{
    /**
     * Retrieve value of a parsable notation
     * Value rapresent ...
     *
     * @param type       $notation
     * @param null|mixed $errors
     *
     * @return type
     */
    public function getNotationValue($notation, &$errors = null)
    {
        $type = $this->getNotationType($notation, $params, $errors);

        if (in_array($type, static::TYPE_WITHOUT_VALUE)) {
            return;
        }

        switch ($type) {
            case 'integer':
                return (int) $notation;
            case 'boolean':
                return (bool) $notation;
            case 'string':
                return isset($params['Default']) ? $params['Default'] : (string) $notation;
            case 'float':
                return (float) $notation;
            case 'double':
                return (float) $notation;
            case 'enum':
                return $params['Default'];
            case 'time':
                return static::parseTime($notation);
            case 'date':
                return static::parseDate($notation);
            case 'datetime':
                return static::parseDatetime($notation);
        }

        $errors[] = "irrational value for '{$notation}' by type '{$type}'";
        // called if detected type not is handled
        //throw new Exception("No PSEUDOTYPE value for '{$type}' => '{$notation}'");
    }
}
