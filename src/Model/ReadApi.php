<?php
/**
 * ModelProtectedAPI.php.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait ReadApi
{
    /**
     * @param type $fields
     *
     * @return type
     */
    public static function all($fields = null)
    {
        static::applySchema();

        $table = static::getTable();
        $writer = static::getDatabase()->getWriter();

        $limit = '';
        if (isset($fields['limit'])) {
            $limit = 'LIMIT '.$fields['limit'];
            unset($fields['limit']);
        }

        $order = '';
        if (isset($fields['order'])) {
            $limit = 'ORDER BY '.$writer->orderBy($fields['order']);
            unset($fields['order']);
        }

        if (isset($fields['fields'])) {
            $fields = array_merge($fields, $fields['fields']);
            unset($fields['fields']);
        }

        $join = '';
        $class = static::getClassName();

        //
        $selectFields = $writer->selectFields($fields, $class, $join);

        //
        $sql = "SELECT {$selectFields} "
             . "FROM {$table} AS {$class} "
             . " {$join} "
             . " {$order} "
             . " {$limit} ";

        //
        try {
            $results = static::fetch(
                $sql,
                null,
                false,
                is_string($fields),
                is_null($fields)
            );
        } catch (DatabaseException $ex) {
            static::error(debug_backtrace(), $ex);
        }

        return $results;
    }

    /**
     * @param null|mixed $query
     * @param null|mixed $fields
     *
     * @return type
     */
    public static function first($query = null, $fields = null, $last = false)
    {
        static::applySchema();

        $key = static::getPrimaryKey();
        $table = static::getTable();
        $writer = static::getDatabase()->getWriter();

        if (!$query && !$fields) {
            $fields = '*';
        } elseif (is_string($query) && !$fields) {
            $fields = explode(',', $query);
            $query = null;
        }

        $order = '';
        if (isset($query['order'])) {
            $order = 'ORDER BY '.$query['order'];
            unset($query['order']);
        } else {
            $order = 'ORDER BY '.'`'.$key.'`'.' '. ($last ? 'DESC' : 'ASC');
        }

        if (!$fields && isset($query['fields'])) {
            $fields = $query['fields'];
            unset($query['fields']);
        }

        $whereArray = [];
        if (isset($query['where'])) {
            $whereArray[] = '('.$query['where'].')';
            unset($query['where']);
        }

        $valueArray = [];
        if (count($query) > 0) {
            $schema = static::getSchemaFields();
            foreach ($schema as $field) {
                if (!isset($query[$field])) {
                    continue;
                }
                $token = ':'.$field;
                $whereArray[] = "`{$field}` = {$token}";
                $valueArray[$token] = $query[$field];
            }
        }

        $join = '';
        $class = static::getClassName();
        $selectFields = $writer->selectFields($fields, $class, $join);
        $where = $writer->whereByArray($whereArray);
        $sql = "SELECT {$selectFields} FROM {$table} AS {$class} {$where} {$order} LIMIT 1";

        $result = static::fetch(
            $sql,
            $valueArray,
            true,
            is_array($fields) && count($fields) == 1,
            $fields == '*'
        );

        return $result;
    }

    /**
     * @param null|mixed $query
     * @param null|mixed $fields
     *
     * @return type
     */
    public static function last($query = null, $fields = null)
    {
        return static::first($query, $fields, 'last');
    }

    /**
     * @param null|mixed $query
     * @param null|mixed $fields
     *
     * @return type
     */
    public static function min($query = null, $fields = null, $max = false)
    {
        static::applySchema();

        $key = static::getPrimaryKey();
        $table = static::getTable();
        $writer = static::getDatabase()->getWriter();

        if (!$query && !$fields) {
            $fields = '*';
        } elseif (is_string($query) && !$fields) {
            $fields = explode(',', $query);
            $query = null;
        }

        $order = '';
        if (isset($query['order'])) {
            $order = 'ORDER BY '.$query['order'];
            unset($query['order']);
        } else {
            $order = 'ORDER BY '.'`'.$key.'`'.' '. ($last ? 'DESC' : 'ASC');
        }

        if (!$fields && isset($query['fields'])) {
            $fields = $query['fields'];
            unset($query['fields']);
        }

        $whereArray = [];
        if (isset($query['where'])) {
            $whereArray[] = '('.$query['where'].')';
            unset($query['where']);
        }

        $valueArray = [];
        if (count($query) > 0) {
            $schema = static::getSchemaFields();
            foreach ($schema as $field) {
                if (!isset($query[$field])) {
                    continue;
                }
                $token = ':'.$field;
                $whereArray[] = "`{$field}` = {$token}";
                $valueArray[$token] = $query[$field];
            }
        }

        $join = '';
        $class = static::getClassName();
        $selectFields = $writer->selectFields($fields, $class, $join);
        $where = $writer->whereByArray($whereArray);
        $sql = "SELECT {$selectFields} FROM {$table} AS {$class} {$where} {$order} LIMIT 1";

        $result = static::fetch(
            $sql,
            $valueArray,
            true,
            is_array($fields) && count($fields) == 1,
            $fields == '*'
        );

        return $result;
    }

    /**
     * @param null|mixed $query
     * @param null|mixed $fields
     *
     * @return type
     */
    public static function max($query = null, $fields = null)
    {
        return static::min($query, $fields, 'max');
    }

    /**
     * Alias of ping.
     *
     * @param type $query
     *
     * @return type
     */
    public static function exists($query)
    {
        //
        static::applySchema();

        //
        $table = self::getTable();

        //
        $whereArray = [];

        //
        $valuesArray = [];

        //
        if (isset($query['where'])) {
            $whereArray[] = $query['where'];
            unset($query['where']);
        }

        //
        $schema = static::getSchemaFields();

        //
        foreach ($schema as $field) {
            if (isset($query[$field])) {
                $token = ':'.$field;
                $whereArray[] = "`{$field}` = {$token}";
                $valuesArray[$token] = $query[$field];
            }
        }

        $where = count($whereArray) > 0
               ? 'WHERE '.implode(' AND ', $whereArray)
               : '';

        $sql = "SELECT * FROM `{$table}` {$where} LIMIT 1";

        $row = static::getDatabase()->getRow($sql, $valuesArray);

        return $row ? self::make($row) : false;
    }

    public static function ping(&$query)
    {
        $exist = static::exists($query);
        $query = $exist ? $exist : static::make($query);

        return $exist;
    }
}
