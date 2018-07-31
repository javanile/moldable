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
             ."FROM {$table} AS {$class} "
             ." {$join} "
             ." {$order} "
             ." {$limit} ";

        $results = static::fetch(
            $sql,
            null,
            false,
            is_string($fields),
            is_null($fields)
        );

        return $results;
    }

    /**
     * @param null|mixed $query
     * @param null|mixed $fields
     * @param mixed      $last
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
            $order = 'ORDER BY '.'`'.$key.'`'.' '.($last ? 'DESC' : 'ASC');
        }

        if (!$fields && isset($query['fields'])) {
            $fields = $query['fields'];
            unset($query['fields']);
        }

        if (!$fields) {
            $fields = '*';
        }

        $whereArray = [];
        if (isset($query['where'])) {
            if (is_array($query['where'])) {
                $query = array_merge($query, $query['where']);
            } else {
                $whereArray[] = '('.$query['where'].')';
            }
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
        $sql = "SELECT {$selectFields} FROM `{$table}` AS {$class} {$where} {$order} LIMIT 1";

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
     * @param mixed      $max
     *
     * @return type
     */
    public static function min($query = null, $fields = null, $max = false)
    {
        static::applySchema();

        $key = static::getPrimaryKey();
        $table = static::getTable();
        $writer = static::getDatabase()->getWriter();

        if (isset($query['fields'])) {
            $fields = array_merge((array) $fields, $query['fields']);
            unset($query['fields']);
        }

        if (!$query && !$fields) {
            $focus = $key;
            $fields = [$key];
        } elseif (is_string($query) && !$fields) {
            $fields = explode(',', $query);
            $focus = $fields[0];
            $query = null;
        } elseif (is_array($fields)) {
            $focus = $fields[0];
        } else {
            $focus = $key;
        }

        $order = '';
        if (isset($query['order'])) {
            $order = 'ORDER BY '.$query['order'].' '.($max ? 'DESC' : 'ASC');
            $fields[] = $query['order'];
            unset($query['order']);
        } else {
            $order = 'ORDER BY '.'`'.$focus.'`'.' '.($max ? 'DESC' : 'ASC');
        }

        $whereArray = [];
        if (isset($query['where'])) {
            if (is_array($query['where'])) {
                $query = array_merge($query, $query['where']);
            } else {
                $whereArray[] = '('.$query['where'].')';
            }
            unset($query['where']);
        }

        if (!$fields) {
            $fields = '*';
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

        return $row ? self::create($row) : false;
    }

    /**
     * Historical version of ::exists().
     *
     * @param mixed $query
     */
    public static function ping($query)
    {
        $exist = static::exists($query);
        //$query = $exist ? $exist : static::create($query);

        return $exist;
    }
}
