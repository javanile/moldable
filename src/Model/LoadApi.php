<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait LoadApi
{
    /**
     * Load item from DB.
     *
     * @param type       $id
     * @param mixed      $query
     * @param null|mixed $fields
     *
     * @return type
     */
    public static function load($query, $fields = null)
    {
        //
        static::applySchema();

        //
        if (is_array($query)) {
            return static::loadByQuery($query, $fields);
        }

        //
        $key = static::getPrimaryKey();

        //
        return $key
             ? static::loadByPrimaryKey($query, $fields)
             : static::loadByMainField($query, $fields);
    }

    /**
     * Load a record by primary key.
     *
     * @param type $index
     * @param type $fields
     *
     * @return type
     */
    protected static function loadByPrimaryKey($index, $fields = null)
    {
        //
        $table = static::getTable();

        // get primary key
        $key = static::getPrimaryKey();

        //
        $alias = static::getClassName();

        //
        $join = null;

        //
        $requestedFields = $fields ? $fields : static::getDefaultFields();

        // parse SQL select fields
        $selectFields = static::getDatabase()
            ->getWriter()
            ->selectFields($requestedFields, $alias, $join);

        // prepare SQL query
        $sql = " SELECT {$selectFields} "
             ."   FROM {$table} AS {$alias} {$join} "
             ."  WHERE {$alias}.{$key}=:index "
             .'  LIMIT 1';

        $params = [
            'index' => $index,
        ];

        // fetch data on database and return it
        $result = static::fetch($sql, $params, [
            'SingleRow'     => true,
            'SingleValue'   => is_string($fields),
            'CastToObject'  => is_null($fields),
            //'ExpanseObject' => static::isReadable(),
            'ExpanseObject' => false,
        ]);

        //
        return $result;
    }

    /**
     * @param type $value
     * @param type $fields
     *
     * @return type
     */
    protected static function loadByMainField($value, $fields = null)
    {
        //
        $table = static::getTable();

        // get main field
        $field = static::getMainField();

        //
        $alias = static::getClassName();

        //
        $join = null;

        //
        $allFields = $fields ? $fields : static::getDefaultFields();

        // parse SQL select fields
        $selectFields = static::getDatabase()
                     ->getWriter()
                     ->selectFields($allFields, $alias, $join);

        //
        $token = ':'.$field;

        //
        $values = [$token => $value];

        // prepare SQL query
        $sql = " SELECT {$selectFields}"
             ."   FROM {$table} AS {$alias} {$join}"
             ."  WHERE {$field} = {$token}"
             .'  LIMIT 1';

        // fetch data on database and return it
        $object = static::fetch(
            $sql,
            $values,
            true,
            is_string($fields)
        );

        //
        return $object;
    }

    /**
     * Load one record by array-query.
     *
     * @param type $query
     * @param type $fields
     *
     * @return type
     */
    protected static function loadByQuery($query, $fields = null)
    {
        //
        $table = static::getTable();

        //
        $alias = static::getClassName();

        //
        $join = null;

        //
        $allFields = $fields ? $fields : static::getDefaultFields();

        // parse SQL select fields
        $selectFields = static::getDatabase()
                     ->getWriter()
                     ->selectFields($allFields, $alias, $join);

        //
        $whereConditions = [];

        //
        if (isset($query['where'])) {
            $whereConditions[] = '('.$query['where'].')';
            unset($query['where']);
        }

        //
        foreach ($query as $field => $value) {
            $token = ':'.$field;

            //
            $whereConditions[] = "{$field} = {$token}";

            //
            $values[$field] = $value;
        }

        //
        $where = implode(' AND ', $whereConditions);

        // prepare SQL query
        $sql = "SELECT {$selectFields} "
             ."FROM {$table} AS {$alias} {$join} "
             ."WHERE {$where} "
             .'LIMIT 1';

        // fetch data on database and return it
        $result = static::fetch(
            $sql,
            $values,
            true,
            is_string($fields),
            is_null($fields)
        );

        return $result;
    }
}
