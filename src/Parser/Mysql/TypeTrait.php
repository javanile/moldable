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
     * @param type       $notation
     * @param type       $params
     * @param null|mixed $namespace
     * @param null|mixed $errors
     *
     * @return string
     */
    public function getNotationType(
        $notation,
        &$params = null,
        &$errors = null,
        $namespace = null
    ) {
        $type = gettype($notation);
        $params = null;

        switch ($type) {
            case 'string':
                return $this->getNotationTypeString($notation, $params, $errors, $namespace);
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

        $errors[] = "irrational type for '{$notation}'";
    }

    /**
     * @param type       $notation
     * @param null|mixed $namespace
     * @param mixed      $params
     * @param mixed      $errors
     */
    private function getNotationTypeString(
        $notation,
        &$params,
        &$errors,
        $namespace = null
    ) {
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
        if ($this->isClass($notation, $matchs)) {
            $params['Class'] = $this->applyNamespace($matchs[1], $namespace);
            if (!class_exists($params['Class'])) {
                $errors[] = "related class not found '{$params['Class']}' for notation '{$notation}'";
            }

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
     * @param type  $notation
     * @param mixed $params
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

    /**
     * Check if notation is a class one-to-one relation.
     *
     * @param mixed $notation
     * @param mixed $matchs
     */
    public function isClass($notation, &$matchs)
    {
        return preg_match(
            '/^<<[ \t]*'.static::REGEX_PHP_CLASS.'[ \t]*>>$/',
            $notation,
            $matchs
        );
    }

    /**
     * @param mixed $notation
     * @param mixed $matchs
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
     * @param mixed $notation
     * @param mixed $matchs
     */
    public static function pregMatchMatchs($notation, &$matchs)
    {
        return preg_match(
            '/^<<'.static::REGEX_PHP_CLASS.'\*\*>>$/',
            $notation,
            $matchs
        );
    }

    /**
     * Apply namespace to a class name.
     *
     * @param mixed $class
     * @param mixed $namespace
     */
    private function applyNamespace($class, $namespace)
    {
        if ($class[0] != '\\' && $namespace) {
            $class = ($namespace != '\\' ? $namespace.'\\' : '\\').$class;
        }

        return $class;
    }
}
