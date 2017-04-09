<?php
/**
 * ModelProtectedAPI.php
 *
 * PHP version 5
 *
 * @category  tag in file comment
 * @package   tag in file comment
 * @license   tag in file comment
 * @link      ciao
 * @author    Francesco Bianco <bianco@javanile.org>
\*/

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Exception;

trait UpdateApi
{
    /**
     *
     * @param  type $query
     * @param  type $values
     * @return type
     */
    public static function update($query, $values=null, $value=null)
    {
        //
        static::applyTable();

        //
        $key = static::getPrimaryKey();
            
        //
        if (!$key) {
            $key = static::getMainField();
        }

        //
        $schema = static::getSchemaFields();

        //
        $valuesArray = [];

        //
        $whereArray = [];

        //
        if (is_null($values)) {
            if ($key && isset($query[$key])) {
                $values = (array) $query;
                $query = $values[$key];
                unset($values[$key]);
            } else {
                static::error('No target records to update');
            }
        } else if (is_string($values)) {
            $values = [$values => $value];
        }

        //
        if (is_array($query)) {
            if ($key && isset($query[$key]) && $query[$key]) {
                $token = ':'.$key.'0';
                $whereArray[] = "`{$key}` = {$token}";
                $valuesArray[$token] = $query[$key];
            } else {
                foreach ($query as $field => $value) {
                    $token = ':'.$field.'0';
                    $whereArray[] = "`{$field}` = {$token}";
                    $valuesArray[$token] = $value;                    
                }
            }
        }

        //
        else if (is_object($query) && is_subclass_of($query, 'Javanile\\SchemaDB\\Storable')) {
                       
            //
            if ($key) {
                $token = ':'.$key.'0';
                $whereArray[] = "`{$key}` = {$token}";
                $valuesArray[$token] = $query->{$key};
            } else {
                echo 'D';
            }
        }

        //
        else {           
            //
            if ($key) {
                $token = ':'.$key.'0';
                $whereArray[] = "`{$key}` = {$token}";
                $valuesArray[$token] = $query;
            } else {
                echo 'E';
            }
        }
        
        //
        $setArray = [];
                
        //
        foreach($schema as $field) {
            if (isset($values[$field])) {
                $token = ':'.$field.'1';
                $setArray[] = "`{$field}` = {$token}";
                $valuesArray[$token] = $values[$field];
            }
        }

        //
        $set = implode(',', $setArray);

        //
        $where = $whereArray ? 'WHERE '.implode(' AND ' ,$whereArray) : '';

        //
        $table = static::getTable();

        //
        $sql = "UPDATE {$table} SET {$set} {$where}";
        
        //
        try { static::getDatabase()->execute($sql, $valuesArray); }

        //
        catch (Exception $e) { static::error($e); }
    }
}