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

trait ReadApi
{
    /**
     *
     *
     * @param type $fields
     * @return type
     */
    public static function all($fields=null)
    {
        //
        static::applyTable();

        //
        if (isset($fields['limit'])) {
            $limit = 'LIMIT '.$fields['limit'];
            unset($fields['limit']);
        } else {
            $limit = '';
        }

        //
        $table = static::getTable();

        //
        $join = '';

        //
        $class = static::getClassName();

        var_Dump($fields);
        
        //
        $selectFields = static::getDatabase()
                     -> getWriter()
                     -> selectFields($fields, $class, $join);

         var_Dump($selectFields);
        
        //
        $sql = "SELECT {$selectFields} "
             .   "FROM {$table} AS {$class} "
             .       " {$join} "
             .       " {$limit} ";

             var_Dump($sql);
             
        //
        try {
            $results = static::fetch(
                $sql,
                null,
                false,
                is_string($fields),
                is_null($fields)
            );
            
            var_Dump($results);
        }

        //
        catch (DatabaseException $ex) {
            static::error(debug_backtrace(), $ex);
        }

        //
        return $results;
    }

    /**
     *
     * @return type
     */
    public static function first($query=null, $fields=null)
    {
        //
        static::applyTable();

        //
        $table = static::getTable();

        //
        $order = isset($query['order'])
               ? 'ORDER BY '.$query['order']
               : '';
        
        //
        unset($query['order']);

        //
        $whereArray = [];

        //
        if (isset($query['where'])) {
            $whereArray[] = '('.$query['where'].')';
            unset($query['where']);
        }
        
        //
        $valueArray = [];

        //
        if (count($query) > 0) {

            //
            $schema = static::getSchemaFields(); 

            //
            foreach ($schema as $field) {
             
                //
                if (!isset($query[$field])) { continue; }

                //
                $token = ':'.$field;

                //
                $whereArray[] = "`{$field}` = {$token}";

                //
                $valueArray[$token] = $query[$field];
            }
        }

        //
        $where = $whereArray
               ? 'WHERE '.implode(' AND ', $whereArray)
               : '';
        
        //
        $sql = "SELECT * FROM {$table} {$where} {$order} LIMIT 1";

        //
        $result = static::fetch(
            $sql,
            $valueArray,
            true,
            is_string($fields),
            is_null($fields)
        );

        //
        return $result;
    }

    /**
     * Alias of ping
     *
     * @param type $query
     * @return type
     */
    public static function exists($query)
    {
        //
        static::applyTable();

        //
        $table = self::getTable();
        
        //
        $whereArray = [];

        //
        $valuesArray = [];

        //
        if (isset($query['where'])) {
            $whereArray[] = $query['where'];
            unset($query['where']);
        }

        //
        $schema = static::getSchemaFields();

        //
        foreach ($schema as $field) {
            if (isset($query[$field])) {
                $token = ':'.$field;
                $whereArray[] = "`{$field}` = {$token}";
                $valuesArray[$token] = $query[$field];
            }
        }

        //
        $where = count($whereArray)>0
               ? 'WHERE '.implode(' AND ',$whereArray)
               : '';

        //
        $sql = "SELECT * FROM `{$table}` {$where} LIMIT 1";

        //
        $r = static::getDatabase()->getRow($sql, $valuesArray);

        //        
        return $r ? self::make($r) : false;
    }

    /**
     *
     *
     */
    public static function ping(&$query)
    {
        //
        $exist = static::exists($query);
        
        //
        $query = $exist ? $exist : static::make($query);

        //
        return $exist;
    }
    
    
    
    
    
    
}
