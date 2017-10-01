<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait DeleteApi
{
    /**
     * Delete element by primary key or query.
     *
     * @param type $query
     */
    public static function delete($query)
    {
        static::applySchema();

        $key = static::getPrimaryKeyOrMainField();

        if ($key && !is_array($query)) {
            $query = [$key => $query];
        }

        $whereArray = [];
        if (isset($query['where'])) {
            $whereArray[] = $query['where'];
            unset($query['where']);
        }

        $params = [];

        foreach ($query as $field => $value) {
            $token = ':'.$field;
            $whereArray[] = "`{$field}` = {$token}";
            $params[$token] = $value;
        }

        $where = $whereArray
               ? 'WHERE '.implode(' AND ', $whereArray)
               : '';

        $table = static::getTable();
        $sql = "DELETE FROM {$table} {$where}";

        static::getDatabase()->execute($sql, $params);
    }
}
