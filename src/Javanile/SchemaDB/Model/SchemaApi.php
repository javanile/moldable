<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

trait SchemaApi
{
    /**
     * Instrospect and retrieve element schema
     *
     * @return type
     */
    public static function getSchema()
    {
        //
        $attribute = 'Schema';

        //
        if (!static::hasClassAttribute($attribute)) {

            //
            $fields = static::getSchemaFieldsWithValues();

            //
            $schema = array();

            //
            if ($fields && count($fields) > 0) {
                foreach ($fields as $name => $value) {
                    $schema[$name] = $value;
                }
            }

            //
            static::getDatabase()->getParser()->parseTable($schema);

            //
            static::setClassAttribute($attribute, $schema);
        }

        //
        return static::getClassAttribute($attribute);
    }

    /**
     * Instrospect and retrieve element schema
     *
     * @return type
     */
    protected static function getSchemaFields()
    {
        //
        $attribute    = 'SchemaFields';

        //
        if (!static::hasClassAttribute($attribute))
        {
            //
            $attibuteLookup = 'SchemaExcludedFields';

            //
            $allFields = array_keys(get_class_vars(static::getClass()));

            //
            $allStaticFields = static::getStaticFields();

            //
            $fields = array_diff(
                $allFields,
                $allStaticFields,
                static::getClassGlobal($attibuteLookup)
            );
            
            //
            if (static::hasClassConfig($attibuteLookup)) {
                $fields = array_diff(
                    $fields,
                    static::getClassConfig($attibuteLookup)
                );
            }

            //
            static::setClassAttribute($attribute, $fields);
        }

        //
        return static::getClassAttribute($attribute);
    }

    /**
     * Instrospect and retrieve element schema
     *
     * @return type
     */
    protected static function getSchemaFieldsWithValues()
    {
        //
        $attribute = 'SchemaFieldsWithValues';

        //
        if (!static::hasClassAttribute($attribute)) {

            //
            $attributeLookup = 'SchemaExcludedFields';

            //
            $fields = static::getAllFieldsWithValues();

            //
            foreach (static::getStaticFields() as $field) {
                unset($fields[$field]);
            }

            //
            foreach(static::getClassGlobal($attributeLookup) as $field) {
                unset($fields[$field]);
            }

            //
            if (static::hasClassConfig($attributeLookup)) {
                foreach(static::getConfig($attributeLookup) as $field) {
                    unset($fields[$field]);
                }
            }

            //
            static::setClassAttribute($attribute, $fields);
        }

        //
        return static::getClassAttribute($attribute);
    }
}