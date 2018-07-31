<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Exception;

trait SchemaApi
{
    /**
     * Apply schema model related.
     *
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

        $schema = static::getSchema();

        if (!$schema) {
            static::error('class', 'empty schema not allowed');
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
            $parser = static::getDatabase()->getParser();
            $schema = [];

            if ($fields && count($fields) > 0) {
                foreach ($fields as $name => $value) {
                    $schema[$name] = $value;
                }
            }

            $parser->parseTable($schema, $errors, static::getNamespace());

            if ($errors) {
                static::error('class', $errors[0]);
            }

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

    /**
     * Describe model.
     */
    public static function desc()
    {
        static::applySchema();

        $table = static::getTable();
        $desc = static::getDatabase()->descTable($table);

        return $desc;
    }

    /**
     * Retriece linked database or default.
     *
     * @return type
     */
    public static function getDatabase()
    {
        $attribute = 'database';

        if (!static::hasClassAttribute($attribute)) {
            $database = Database::getDefault();

            if (!$database) {
                $error = static::error('connection', 'database not found', 'required-for', 6);
                switch (static::getClassConfig('error-mode')) {
                    case 'silent': break;
                    case 'exception': throw new Exception($error);
                    default: trigger_error($error, E_USER_ERROR);
                }
            }

            static::setClassAttribute($attribute, $database);
        }

        return static::getClassAttribute($attribute);
    }

    /**
     * Link specific database to this table.
     *
     * @param mixed $database
     *
     * @return type
     */
    public static function setDatabase($database)
    {
        $attribute = 'database';

        static::setClassAttribute($attribute, $database);
    }
}
