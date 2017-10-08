<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait UpdateApi
{
    /**
     * @param type       $query
     * @param type       $values
     * @param null|mixed $map
     *
     * @return type
     */
    public static function update($query, $values = null, $map = null)
    {
        static::applySchema();

        $key = static::getPrimaryKeyOrMainField();

        if ($key && !is_array($query)) {
            $query = [$key => $query];
        }

        if ($key && isset($query[$key]) && is_null($values)) {
            $values = $query;
            $query = [$key => $query[$key]];
            unset($values[$key]);
        }

        if ($values && is_string($values)) {
            $values = [$values => $map];
        }

        $params = [];
        $setArray = [];
        $whereArray = [];

        foreach ($query as $field => $value) {
            $token = ':'.$field.'0';
            $whereArray[] = "`{$field}` = {$token}";
            $params[$token] = $value;
        }

        foreach (static::getSchemaFields() as $field) {
            if (!isset($values[$field])) {
                continue;
            }

            $token = ':'.$field.'1';
            $setArray[] = "`{$field}` = {$token}";
            $params[$token] = $values[$field];
        }

        $set = implode(',', $setArray);
        $where = $whereArray ? 'WHERE '.implode(' AND ', $whereArray) : '';
        $table = static::getTable();
        $sql = "UPDATE `{$table}` SET {$set} {$where}";

        static::getDatabase()->execute($sql, $params);
    }
}
