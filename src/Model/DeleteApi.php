<?php
/**
 * Trait that handle the delete operation.
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

        $writer = static::getDatabase()->getWriter();

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
            $quotedField = $writer->quote($field);
            $whereArray[] = "{$quotedField} = {$token}";
            $params[$token] = $value;
        }

        $where = $whereArray
               ? 'WHERE '.implode(' AND ', $whereArray)
               : '';

        $table = static::getTable();
        $quotedTable = $writer->quote($table);
        $sql = "DELETE FROM {$quotedTable} {$where}";

        static::getDatabase()->execute($sql, $params);
    }
}
