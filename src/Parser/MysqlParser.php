<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser;

class MysqlParser implements Parser
{
    use Mysql\KeyTrait;
    use Mysql\TypeTrait;
    use Mysql\EnumTrait;
    use Mysql\ValueTrait;
    use Mysql\NumberTrait;
    use Mysql\StringTrait;
    use Mysql\CommonTrait;
    use Mysql\RelationTrait;
    use Mysql\DatetimeTrait;

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
        foreach ($schema as &$table) {
            static::parseTable($table);
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
    public function parseTable(&$table, &$errors = null, $namespace = '\\')
    {
        // for first field no have before
        $before = false;

        // loop throuh fields on table
        foreach ($table as $field => &$notation) {
            $notation = static::getNotationAspects(
                $notation,
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
     * @param type       $notation
     * @param type       $field
     * @param type       $before
     * @param null|mixed $namespace
     * @param null|mixed $errors
     *
     * @return string
     */
    public function getNotationAspects(
        $notation,
        $field = null,
        $before = null,
        &$errors = null,
        $namespace = null
    ) {
        $params = null;
        $type = $this->getNotationType($notation, $params, $errors, $namespace);
        $aspects = $this->getNotationCommonAspects($field, $before);

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
    private function getNotationCommonAspects($field, $before)
    {
        $aspects = [
            'Field'    => null,
            'Key'      => '',
            'Type'     => '',
            'Null'     => 'YES',
            'Extra'    => '',
            'Default'  => '',
            'Relation' => null,
        ];

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
