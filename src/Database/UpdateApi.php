<?php
/**
 * Collect API to handle fields of a model.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Database;

trait UpdateApi
{
    /**
     * Retrieve primary key name of specific model.
     *
     * @param type $model
     * @return type
     */
    public function update($model, $query, $values = null, $value = null)
    {
        $key = $this->getPrimaryKey($model);

        if (!is_array($query)) {
            $query = [
                $key => $query
            ];
        }

        if (is_string($values)) {
            $values = [$values => $value];
            $value  = null;
        }

        if (is_null($values)) {
            $values = $query;
            $query = [$key => $query[$key]];
        }

        $params   = [];
        $setArray = [];

        foreach ($values as $field => $value) {
            $token          = ':'.$field;
            $setArray[]     = "`{$field}` = {$token}";
            $params[$token] = $value;
        }

        $set   = implode(',', $setArray);
        $table = $this->getPrefix($model);
        $where = $this->getUpdateWhere($model, $query, $params);
        $sql   = "UPDATE `{$table}` SET {$set} WHERE {$where}";

        $this->execute($sql, $params);
    }

    /**
     *
     *
     */
    private function getUpdateWhere($model, $query, &$params)
    {
        $whereArray = [];

        if (isset($query['where'])) {
            $where[] = '('.$query['where'].')';
            unset($query['where']);
        }

        foreach ($query as $field => $value) {
            if ($field[0] == ':') {
                $params[$field] = $value;
                continue;
            }
            $token          = ':'.$field;
            $whereArray[]   = "{$field} = {$token}";
            $params[$token] = $value;
        }

        return implode(' AND ', $whereArray);
    }
}