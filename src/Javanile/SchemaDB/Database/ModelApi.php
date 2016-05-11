<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.4
 *
 * @author Francesco Bianco 
 */
namespace Javanile\SchemaDB\Database;

use Javanile\SchemaDB\Functions;

trait ModelApi 
{
    /**
     * Retrieve the table-name of specifc model.
     *
     * @param string $model Model name
     * @return string Return table name by model
     */
    private function getTable($model)
    {
        // use prefix on model name
        return $this->getPrefix($model);
    }

    /**
     *
     *
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
     * describe table
     *
     * @param type $table
     * @return type
     */
    public function getFields($model)
    {
        //
        $table = $this->getTable($model);

        //
        $sql = "DESC `{$table}`";

        //
        $results = $this->getResults($sql);

        //
        $fields = [];

        //
        foreach ($results as $field) {
            $fields[] = $field['Field'];
        }

        //
        return $fields;
    }

    /**
     *
     *
	 * @param type $fields
     * @return type
     */
    public function all($model, $fields=null)
    {
        //
        $table = $this->getPrefix($model);

        //
        $sql = "SELECT * FROM `{$table}`";

        //
        $results = $this->getResults($sql);

        //
        return $results;
    }

    /**
     *
     * @param type $list
     */
    public function insert($model, $values, $map=null) 
    {
        //
        $this->adapt($model, $this->profile($values));

        // collect field names for sql query
        $fieldsArray = array();

        // collect tokens for sql query
        $tokensArray = array();

        // collect values for sql query
        $valuesArray = array();

        //
        foreach ($values as $field => $value) {

            //
            $field = isset($map[$field]) ? $map[$field] : $field;

            //
            $token = ':'.$field;

            //
            $fieldsArray[] = $field;
            $tokensArray[] = $token;

            //
            $valuesArray[$token] = $value;
        }

        //
        $fields = implode(',', $fieldsArray);
        $tokens = implode(',', $tokensArray);

        //
        $table = $this->getPrefix($model);

        //
        $sql = "INSERT INTO `{$table}` ({$fields}) VALUES ({$tokens})";

        //
        $this->execute($sql, $valuesArray);

        //
        return $this->getLastId();
    }

    /**
     *
     * @param type $list
     */
    public function update($model, $query, $values, $map=null) {

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
    private function getUpdateWhere($model, $query, &$values) {

        if (is_array($query)) {


        } else {

            $key = $this->getPrimaryKey($model);

            $field = $key ? $key : $this->getMainField($model) ;

            $token = ':'.$field;

            $values[$token] = $query;

            return "{$field} = {$token}";
        }
    }

    /**
     *
     *
     */
    public function exists($model, $query)
    {
        //
        $params = [];

        //
        $whereArray = [];

        //
        if (isset($query['where'])) {
            $whereArray[] = $query['where'];
            unset($query['where']);
        }
        
        //
        $schema = $this->getFields($model);
        
        //
        foreach ($schema as $field) {
            
            //
            if (!isset($query[$field])) { continue; }

            //
            $value = $query[$field];
            
            //
            $token = ':'.$field;
        
            //
            $params[$token] = $value;

            //
            $whereArray[] = "{$field} = {$token}";
        }

        //
        $where = count($whereArray) > 0
               ? 'WHERE '.implode(' AND ', $whereArray) : '';

        //
        $table = $this->getPrefix($model);

        //
        $sql = "SELECT * FROM `{$table}` {$where} LIMIT 1";

        //
        $row = $this->getRow($sql, $params);

        //
        return $row;
    }

    /**
     *
     *
     * @param type $list
     */
    public function import($model, $records, $map=null)
    {
        //
        if (!$records || !is_array($records[0])) {
            return;
        }

        //
        foreach($records as $record) {
            
            //
            $schema = [];

            //
            foreach (array_keys($record) as $field) {
                $schema[$field] = '';
            }

            //
            $this->adapt($model, $schema);
          
            //
            $this->submit($model, $record);
        }
    }

    /**
     *
     *
     */
    public function submit($model, $values) {

        //
        $exists = $this->exists($model, $values);

        //
        if (!$exists) {
            $exists = $this->insert($model, $values);
        }
        
        //
        return $exists;
    }

    /**
     * Drop delete table related to a model.
     *
     * @param string $model Model name to drop
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

            //
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
     *
     *
     */
    public function dump($model=null) {

        //
        if ($model) {

            //
            $all = $this->all($model);

            //
            Functions::gridDump($model, $all);
        }

        //
        else {
            $this->dumpSchema();
        }
    }
}