<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.4
 *
 * @author Francesco Bianco
 */
namespace Javanile\SchemaDB\Database;

trait ModelUpdateApi
{
    /**
     *
     * @param type $list
     */
    public function update($model, $query, $values, $map=null)
    {
        //
        if (is_string($values)) {
            $values = [
                $values => $map,
            ];
            $map = null;
        }

        //
        $setArray = array();

        //
        $valuesArray = array();

        //
        foreach ($values as $field => $value) {

            //
            $token = ':'.$field;

            //
            $setArray[] = "{$field} = {$token}";

            //
            $valuesArray[$token] = $value;
        }

        //
        $set = implode(',', $setArray);

        //
        $table = $this->getPrefix($model);

        //
        $where = $this->getUpdateWhere($model, $query, $values);

        //
        $sql = "UPDATE `{$table}` SET {$set} WHERE {$where}";

        //
        $this->execute($sql, $values);
    }

    /**
     *
     *
     */
    private function getUpdateWhere($model, $query, &$params)
    {
        //
        if (is_array($query)) {

            //
            $where = [];
            
            //
            if (isset($query['where'])) {
                $where[] = '('.$query['where'].')';
                unset($query['where']);
            }
            
            //
            foreach ($query as $field => $value) {

                //
                if ($field[0] == ':') {
                    $params[$field] = $value;
                    continue;
                }

                //
                $token = ':'.$field;

                //
                $params[$token] = $value;

                //
                $where[] = "{$field} = {$token}";
            }

            //
            return implode(' AND ', $where);
        }

        //
        else {

            $key = $this->getPrimaryKey($model);

            $field = $key ? $key : $this->getMainField($model);

            $token = ':'.$field;

            $params[$token] = $query;

            return "{$field} = {$token}";
        }
    }
}