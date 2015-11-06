<?php

/**
 * 
 */
namespace Javanile\SchemaDB;

/**
 *
 */
class DatabaseRecord extends DatabaseModel
{
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
    public function insert($model, $values, $map=null) {

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
		return true;
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
     */
    public function exists($model, $query)
    {
        //
        $valuesArray = array();

		//
		$whereConditions = array();

        //
        if (isset($query['where'])) {
            $whereConditions[] = $query['where'];
            unset($query['where']);
        }

        //
        $schema = $this->getFields($model);
        
        //
        foreach ($schema as $field) {
            
            //
            if (!isset($query[$field])) {
                continue;
            }

            //
            $value = $query[$field];
            
            //
            $token = ':'.$field;
        
            //
            $valuesArray[$token] = $value;

            //
            $whereConditions[] = "{$field} = {$token}";
        }

        //
        $where = count($whereConditions)>0 ? 'WHERE '.implode(' AND ',$whereConditions) : '';

        //
        $table = $this->getPrefix($model);

        //
        $sql = "SELECT * FROM `{$table}` {$where} LIMIT 1";

        //
        $row = $this->getRow($sql, $valuesArray);

        //
        return $row;
    }


    /**
     *
     * @param type $list
     */
    public function import($model, $records, $map=null) {

        //
        if (!is_array($records[0]) || !$records) {
            return;
        }

        //
        foreach($records as $record) {
            
            //
            $schema = array();

            //
            foreach(array_keys($record) as $field) {
                $schema[$field] = '';
            }

            //
            $this->apply($model, $schema);

            //
            $this->submit($model, $record);
        }
    }

    /**
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
}