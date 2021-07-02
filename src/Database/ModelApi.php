<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.4
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database;

use Javanile\Moldable\Functions;

trait ModelApi
{
    /**
     * Retrieve the table-name of specifc model.
     *
     * @param string $model Model name
     *
     * @return string Return table name by model
     */
    private function getTable($model)
    {
        // use prefix on model name
        return $this->getPrefix($model);
    }

    /**
     * Get all models.
     */
    public function getModels()
    {
        //
        $models = $this->getTables();

        //
        $prefix = strlen($this->getPrefix());

        //
        if (count($models) > 0) {
            foreach ($models as &$table) {
                $table = substr($table, $prefix);
            }
        }

        //
        return $models;
    }

    /**
     * Describe table.
     *
     * @param type  $table
     * @param mixed $model
     *
     * @return type
     */
    public function getFields($model)
    {
        $table = $this->getTable($model);
        $writer = $this->getWriter();
        $sql = $writer->descTable($table);
        $results = $this->getResults($sql);

        $fields = [];
        foreach ($results as $field) {
            $fields[] = $field['Field'];
        }

        return $fields;
    }

    /**
     * Get all records.
     *
     * @param type  $fields
     * @param mixed $model
     *
     * @return type
     */
    public function all($model, $fields = null)
    {
        $table = $this->getPrefix($model);
        $order = '';

        if (isset($fields['order'])) {
            $order = 'ORDER BY '.$fields['order'];
            unset($fields['order']);
        }

        $quotedTable = $this->getWriter()->quote($table);
        $sql = "SELECT * FROM {$quotedTable} {$order}";
        $results = $this->getResults($sql);

        return $results;
    }

    /**
     * Check if record exists.
     *
     * @param mixed $model
     * @param mixed $query
     */
    public function exists($model, $query)
    {
        $params = [];
        $whereArray = [];
        $writer = $this->getWriter();

        if (isset($query['where'])) {
            $whereArray[] = $query['where'];
            unset($query['where']);
        }

        $schema = $this->getFields($model);
        foreach ($schema as $field) {
            if (!isset($query[$field])) {
                continue;
            }
            $value = $query[$field];
            $token = ':'.$field;
            $params[$token] = $value;
            $quotedField = $writer->quote($field);
            $whereArray[] = "{$quotedField} = {$token}";
        }

        $where = count($whereArray) > 0
               ? 'WHERE '.implode(' AND ', $whereArray) : '';

        $table = $this->getPrefix($model);
        $quotedTable = $writer->quote($table);

        $sql = "SELECT * FROM {$quotedTable} {$where} LIMIT 1";

        $row = $this->getRow($sql, $params);

        return $row;
    }

    /**
     * Import records into a model table.
     *
     * @param type  $list
     * @param mixed $model
     * @param mixed $records
     */
    public function import(
        $model,
        $records
        //$map = null
    ) {
        if (!$records || !is_array($records[0])) {
            return;
        }

        foreach ($records as $record) {
            $schema = [];

            foreach (array_keys($record) as $field) {
                $schema[$field] = '';
            }

            $this->adapt($model, $schema);
            $this->submit($model, $record);
        }
    }

    /**
     * Submit (upsert) record into a model table.
     *
     * @param mixed $model
     * @param mixed $values
     */
    public function submit($model, $values)
    {
        $exists = $this->exists($model, $values);

        if (!$exists) {
            $exists = $this->insert($model, $values);
        }

        return $exists;
    }

    /**
     * Drop delete table related to a model.
     *
     * @param string $model   Model name to drop
     * @param string $confirm Confirmation string
     */
    public function drop($model, $confirm)
    {
        // exit if no correct confirmation string
        if ($confirm != 'confirm') {
            return;
        }

        //
        $models = $model == '*'
                ? $this->getModels()
                : [$model];

        //
        if (!count($models)) {
            return;
        }

        //
        foreach ($models as $model) {
            $table = $this->getTable($model);

            //
            if (!$table) {
                continue;
            }

            //
            $sql = "DROP TABLE `{$table}`";

            //
            $this->execute($sql);
        }
    }

    /**
     * Dump all data.
     *
     * @param null|mixed $model
     */
    public function dump($model = null)
    {
        if ($model) {
            $all = $this->all($model);
            Functions::dumpGrid($all, $model);
        } else {
            $this->dumpSchema();
        }
    }
}
