<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser;

class MysqlParser extends Parser
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
     * parse a multi-table schema to sanitize end explode implicit info
     *
     * @param type $schema
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
            foreach($table as $aspects) {
                if (isset($aspects['Relation']) && $aspects['Relation'] == 'many-to-many') {
                    $schema['Cioa'] = [[]];
                }
            }
        }
    }

    /**
     * Parse table schema to sanitize end explod implicit info
     *
     * @param type $schema
     * @return type
     */
    public function parseTable(&$table)
    {        
        // for first field no have before 
        $before = false;

        // loop throuh fields on table
        foreach ($table as $field => &$notation) {
            $notation = static::getNotationAspects($notation, $field, $before);
            $before = $field;
        }       
    }

    /**
     * Parse notation of a field
     *
     * @param  type   $notation
     * @param  type   $field
     * @param  type   $before
     * @return string
     */ 
    public function getNotationAspects(
        $notation,
        $field = null,
        $before = null
    ) {
        $params = null;
        $type = $this->getNotationType($notation, $params);
        $aspects = $this->getNotationCommonAspects($field, $before);

        // look-to type
        switch ($type) {
            case 'schema':
                return $this->getNotationAspectsSchema($notation, $aspects);
            case 'json':
                return static::getNotationAttributesJson($notation, $field, $before);
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

            //
            case 'boolean':
                return static::getNotationAttributesBoolean($notation, $field, $before);

            //
            case 'integer':
                return $this->getNotationAspectsInteger($notation, $aspects);
                
            //
            case 'float':
                return static::getNotationAttributesFloat($notation, $field, $before);
            
            //
            case 'double':
                return static::getNotationAttributesDouble($notation, $field, $before);

            //
            case 'enum':
                return $this->getNotationAspectsEnum($notation, $aspects);

            //
            case 'class':
                return static::getNotationAttributesClass($notation, $field, $before, $params);

            //
            case 'vector':
                return static::getNotationAttributesVector($notation, $field, $before, $params);

            //
            case 'matchs':
                return static::getNotationAttributesMatchs($notation, $field, $before, $params);

            //
            case 'null':
                return static::getNotationAttributesNull($notation, $field, $before);

            //
            default: trigger_error('Error parse type: '.$field.' ('.$type.')');
        }
    }

    /**
     *
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
