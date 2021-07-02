<?php
/**
 * Collect API to insert data in a model.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database;

trait InsertApi
{
    /**
     * Insert record for specific model with values.
     *
     * @param type       $list
     * @param mixed      $model
     * @param mixed      $values
     * @param null|mixed $map
     */
    public function insert($model, $values, $map = null)
    {
        $writer = $this->getWriter();

        if (is_string($values)) {
            $values = [
                $values => $map,
            ];
            $map = null;
        }

        $this->adapt($model, $this->profile($values));

        $fieldsArray = [];
        $tokensArray = [];
        $valuesArray = [];

        foreach ($values as $field => $value) {
            $field = isset($map[$field]) ? $map[$field] : $field;
            $token = ':'.$field;
            $fieldsArray[] = $writer->quote($field);
            $tokensArray[] = $token;
            $valuesArray[$token] = $value;
        }

        $fields = implode(',', $fieldsArray);
        $tokens = implode(',', $tokensArray);
        $table = $this->getPrefix($model);
        $quotedTable = $writer->quote($table);

        $sql = "INSERT INTO {$quotedTable} ({$fields}) VALUES ({$tokens})";

        $this->execute($sql, $valuesArray);

        return $this->getLastId();
    }
}
