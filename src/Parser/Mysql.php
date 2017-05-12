<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

class Mysql extends Parser
{
    use Number;
    use Relation;
    use Datetime;

    /**
     * parse a multi-table schema to sanitize end explode implicit info
     *
     * @param type $schema
     * @return type
     */
    public function parse(&$schema)
    {
        // loop throh tables on the schema
        foreach($schema as &$table) {
            static::parseTable($table);
        }

        // loop throh tables on the schema
        foreach($schema as &$table) {
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
        $type = static::getNotationType($notation, $params);
        $aspects = $this->getNotationCommonAspects($field, $before);

        // look-to type
        switch ($type) {
            case 'schema':
                return static::getNotationAttributesSchema
                    ($notation, $field, $before);
                
            // notation contain a field attributes written in json 
            case 'json':
                return static::getNotationAttributesJson
                    ($notation, $field, $before);

            // notation contain a date format string
            case 'date':
                return static::getNotationAttributesDate
                    ($notation, $field, $before);

            // notation contain a date format string
            case 'time':
                return static::getNotationAttributesTime
                    ($notation, $field, $before);

            //
            case 'datetime':
                return static::getNotationAttributesDatetime
                    ($notation, $field, $before);
            
            //
            case 'timestamp':
                return static::getNotationAttributesTimestamp
                    ($notation, $field, $before);

            //
            case 'primary_key':
                return static::getNotationAttributesPrimaryKey
                    ($notation, $field, $before, $params);
               
            //
            case 'string':
                return static::getNotationAttributesString
                    ($notation, $field, $before);
               
            //
            case 'boolean':
                return static::getNotationAttributesBoolean
                    ($notation, $field, $before);

            //
            case 'integer':
                return static::getNotationAttributesInteger
                    ($notation, $field, $before);
                
            //
            case 'float':
                return static::getNotationAttributesFloat
                    ($notation, $field, $before);
            
            //
            case 'double':
                return static::getNotationAttributesDouble
                    ($notation, $field, $before);

            //
            case 'enum':
                return static::getNotationAttributesEnum
                    ($notation, $field, $before);

            //
            case 'class':
                return static::getNotationAttributesClass
                    ($notation, $field, $before, $params);

            //
            case 'vector':
                return static::getNotationAttributesVector
                    ($notation, $field, $before, $params);

            //
            case 'matchs':
                return static::getNotationAttributesMatchs
                    ($notation, $field, $before, $params);

            //
            case 'null':
                return static::getNotationAttributesNull
                    ($notation, $field, $before);

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
