<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser;

class Parser
{
    /**
     *
     */
    const REGEX_PHP_CLASS = '(\\\\?[A-Za-z_][0-9A-Za-z_]*(\\\\[A-Za-z_][0-9A-Za-z_]*)*)([^\*.]*)';

    /**
     *
     */
    const TYPE_WITHOUT_VALUE = [
        'primary_key',
        'class',
        'vector',
        'matchs',
        'text',
        'array',
        'timestamp',
        'schema',
        'column',
        'json',
        'null',
    ];

    /**
     * parse a multi-table schema to sanitize end explode implicit info.
     *
     * @param type $schema
     *
     * @return type
     */
    public function parse(&$schema)
    {
        // loop throh tables on the schema
        foreach ($schema as $tableName => &$table) {
            static::parseTable($table, $tableName);
        }

        // loop throh tables on the schema
        foreach ($schema as &$table) {
            foreach ($table as $aspects) {
                if (isset($aspects['Relation']) && $aspects['Relation'] == 'many-to-many') {
                    $schema['Cioa'] = [[]];
                }
            }
        }
    }

    /**
     * Parse table schema to sanitize end explod implicit info.
     *
     * @param type       $schema
     * @param mixed      $namespace
     * @param mixed      $table
     * @param null|mixed $errors
     *
     * @return type
     */
    public function parseTable(&$schema, $table, &$errors = null, $namespace = '\\')
    {
        // for first field no have before
        $before = false;

        // loop fields on table
        foreach ($schema as $field => &$notation) {
            $notation = static::getNotationAspects(
                $notation,
                $table,
                $field,
                $before,
                $errors,
                $namespace
            );
            $before = $field;
        }
    }

    /**
     * Parse notation of a field.
     *
     * @param type $notation
     * @param null $table
     * @param type $field
     * @param type $before
     * @param null|mixed $errors
     *
     * @param null|mixed $namespace
     * @return string
     */
    public function getNotationAspects(
        $notation,
        $table = null,
        $field = null,
        $before = null,
        &$errors = null,
        $namespace = null
    ) {
        $params = null;
        $type = $this->getNotationType($notation, $params, $errors, $namespace);
        $aspects = $this->getNotationCommonAspects($table, $field, $before);

        // Looking type
        switch ($type) {
            case 'schema':
                return $this->getNotationAspectsSchema($notation, $aspects);
            case 'json':
                return $this->getNotationAspectsJson($notation, $aspects);
            case 'date':
                return $this->getNotationAspectsDate($notation, $aspects);
            case 'time':
                return $this->getNotationAspectsTime($notation, $aspects);
            case 'datetime':
                return $this->getNotationAspectsDatetime($notation, $aspects);
            case 'timestamp':
                return $this->getNotationAspectsTimestamp($notation, $aspects);
            case 'primary_key':
                return $this->getNotationAspectsPrimaryKey($notation, $aspects, $params);
            case 'string':
                return $this->getNotationAspectsString($notation, $aspects);
            case 'text':
                return $this->getNotationAspectsText($notation, $aspects);
            case 'null':
                return $this->getNotationAspectsNull($notation, $aspects);
            case 'boolean':
                return $this->getNotationAspectsBoolean($notation, $aspects);
            case 'integer':
                return $this->getNotationAspectsInteger($notation, $aspects);
            case 'float':
                return $this->getNotationAspectsFloat($notation, $aspects);
            case 'double':
                return $this->getNotationAspectsDouble($notation, $aspects);
            case 'enum':
                return $this->getNotationAspectsEnum($notation, $aspects);
            case 'class':
                return static::getNotationAspectsClass($notation, $aspects, $params, $namespace);
            case 'vector':
                return static::getNotationAspectsVector($notation, $aspects, $params, $namespace);
            case 'matchs':
                return static::getNotationAspectsMatchs($notation, $aspects, $params, $namespace);
        }

        $errors[] = "irrational notation '{$notation}' by type '{$type}'";
    }

    /**
     * Get common or default aspects.
     *
     * @param mixed $field
     * @param mixed $before
     */
    protected function getNotationCommonAspects($table, $field, $before)
    {
        $aspects = [
            'Table'    => null,
            'Field'    => null,
            'Key'      => '',
            'Type'     => '',
            'Null'     => 'YES',
            'Extra'    => '',
            'Default'  => '',
            'Relation' => null,
        ];

        if (!is_null($table)) {
            $aspects['Table'] = $table;
        }

        if (!is_null($field)) {
            $aspects['Field'] = $field;
        }

        if (!is_null($before)) {
            $aspects['First'] = !$before;
            $aspects['Before'] = $before;
        }

        return $aspects;
    }
}
