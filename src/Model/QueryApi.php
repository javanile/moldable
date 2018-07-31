<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait QueryApi
{
    /**
     * Query a list of records.
     *
     * @param type $query
     *
     * @return type
     */
    public static function query($query)
    {
        //
        static::applySchema();

        //
        $table = self::getTable();
        $whereArray = [];

        //
        if (isset($query['where'])) {
            $whereArray[] = '('.$query['where'].')';
            unset($query['where']);
        }

        //
        foreach ($query as $field => $value) {
            if (in_array($field, ['order', 'limit'])) {
                continue;
            }
            $whereArray[] = "{$field} = '{$value}'";
        }

        //
        $where = count($whereArray) > 0 ? 'WHERE '.implode(' AND ', $whereArray) : '';
        $order = isset($query['order']) ? 'ORDER BY '.$query['order'] : '';
        $limit = isset($query['limit']) ? 'LIMIT '.$query['limit'] : '';
        $sql = "SELECT * FROM {$table} {$where} {$order} {$limit}";
        $results = static::getDatabase()->getResults($sql);

        //
        foreach ($results as &$record) {
            $record = static::create($record);
        }

        return $results;
    }
}
