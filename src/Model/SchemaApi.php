<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait SchemaApi
{
    /**
     * @return type
     */
    public static function applySchema()
    {
        //if (static::isAdamantTable()) {
        //    return;
        //}

        $attribute = 'apply-schema';

        if (static::hasClassAttribute($attribute)) {
            return true;
        }

        $database = static::getDatabase();

        if (!$database) {
            static::error('database not found', debug_backtrace(), 2);
        }

        $schema = static::getSchema();

        if (!$schema) {
            $reflector = new \ReflectionClass(static::getClass());
            static::error('model class without attributes', [[
                'file' => $reflector->getFileName(),
                'line' => $reflector->getStartLine(),
            ]]);
        }

        $table = static::getTable();

        $queries = $database->applyTable($table, $schema, false);

        static::setClassAttribute($attribute, microtime(true));

        return $queries;
    }

    /**
     * Instrospect and retrieve element schema.
     *
     * @return type
     */
    public static function getSchema()
    {
        $attribute = 'schema';

        if (!static::hasClassAttribute($attribute)) {
            $fields = static::getSchemaFieldsValues();
            $schema = [];

            if ($fields && count($fields) > 0) {
                foreach ($fields as $name => $value) {
                    $schema[$name] = $value;
                }
            }

            static::getDatabase()->getParser()->parseTable($schema);

            static::setClassAttribute($attribute, $schema);
        }

        return static::getClassAttribute($attribute);
    }

    /**
     * Instrospect and retrieve element schema.
     *
     * @return type
     */
    protected static function getSchemaFields()
    {
        $attribute = 'schema-fields';

        if (!static::hasClassAttribute($attribute)) {
            $allFields = static::getAllFields();
            $excludeFields = static::getExcludeFields();
            $schemaFields = array_diff($allFields, $excludeFields);

            static::setClassAttribute($attribute, $schemaFields);
        }

        return static::getClassAttribute($attribute);
    }

    protected static function getExcludeFields()
    {
        $attribute = 'exclude-fields';

        if (!static::hasClassAttribute($attribute)) {
            $excludeFields = static::getStaticFields();

            if (static::hasClassGlobal($attribute)) {
                $excludeFields = array_merge(
                    $excludeFields,
                    static::getClassGlobal($attribute)
                );
            }

            if (static::hasClassConfig($attribute)) {
                $excludeFields = array_merge(
                    $excludeFields,
                    static::getClassGlobal($attribute)
                );
            }

            static::setClassAttribute($attribute, $excludeFields);
        }

        return static::getClassAttribute($attribute);
    }

    /**
     * Instrospect and retrieve element schema.
     *
     * @return type
     */
    protected static function getSchemaFieldsValues()
    {
        $attribute = 'schema-fields-values';

        if (!static::hasClassAttribute($attribute)) {
            $fields = static::getAllFieldsValues();

            foreach (static::getExcludeFields() as $field) {
                unset($fields[$field]);
            }

            static::setClassAttribute($attribute, $fields);
        }

        return static::getClassAttribute($attribute);
    }

    public static function desc()
    {
        static::applySchema();

        $table = static::getTable();
        $desc = static::getDatabase()->descTable($table);

        return $desc;
    }
}
