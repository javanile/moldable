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
     * @param type $list
     */
    public function import($model, $records, $map=null) {

        //
        foreach($records as $record) {

            //
            $this->insert($model, $record);
        }
    }



}