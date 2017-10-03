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
     * Get type of a notation.
     *
     * @param type $notation
     * @param type $params
     *
     * @return string
     */
    public function getNotationType($notation, &$params = null, &$errors = null)
    {
        $type = gettype($notation);
        $params = null;

        switch ($type) {
            case 'string':
                return $this->getNotationTypeString($notation, $params);
            case 'array':
                return $this->getNotationTypeArray($notation, $params);
            case 'integer':
                return 'integer';
            case 'double':
                return 'float';
            case 'boolean':
                return 'boolean';
            case 'NULL':
                return 'null';
        }

        $errors[] = "No PSEUDOTYPE value for '{$type}' => '{$notation}'";

        // called if detected type not is handled
        //throw new Exception("No PSEUDOTYPE value for '{$type}' => '{$notation}'");
    }

    /**
     * @param type $notation
     */
    private function getNotationTypeString($notation, &$params)
    {
        $matchs = null;
        $params = null;

        // simple type
        if (preg_match('/^<<@([a-z_]+)>>$/', $notation, $matchs)) {
            return $matchs[1];
        }

        // type with default value
        if (preg_match('/^<<@([a-z_]+) (.*)>>$/', $notation, $matchs)) {
            $params = [
                'Default' => $matchs[1] != 'text' ? $matchs[2] : null,
            ];

            return $matchs[1];
        }

        // sconosciuto???
        if (preg_match('/^<<primary key ([1-9][0-9]*)>>$/', $notation, $matchs)) {
            $params = array_slice($matchs, 1);

            return 'primary_key';
        }

        // is class relation
        if (static::isClass($notation, $matchs)) {
            $params['Class'] = $matchs[1];

            return 'class';
        }

        //
        if (static::pregMatchVector($notation, $matchs)) {
            return 'vector';
        }

        //
        if (static::pregMatchMatchs($notation, $matchs)) {
            return 'matchs';
        }

        //
        if (preg_match('/^<<\{.*\}>>$/si', $notation)) {
            return 'json';
        }

        // Parse enum
        if (preg_match('/^<<\[.*\]>>$/si', $notation)) {
            $enum = $this->parseEnumNotation($notation);
            $params['Default'] = isset($enum[0]) ? $enum[0] : null;

            return 'enum';
        }

        if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/', $notation)) {
            return 'datetime';
        }

        if (preg_match('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/', $notation)) {
            return 'date';
        }

        if (preg_match('/^[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/', $notation)) {
            return 'time';
        }

        return 'string';
    }

    /**
     * @param type $notation
     *
     * @return string
     */
    private function getNotationTypeArray($notation, &$params)
    {
        if ($notation && $notation == array_values($notation)) {
            $params['Default'] = isset($notation[0]) ? $notation[0] : null;

            return 'enum';
        }

        return 'schema';
    }
}
