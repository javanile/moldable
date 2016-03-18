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

trait LoadApi
{
    /**
     * Load item from DB
     *
     * @param  type $id
     * @return type
     */
    public static function load($query, $fields=null)
    {
        // update table schema on DB
        static::applyTable();

        //
        if (is_array($query)) {
            return static::loadByQuery($query, $fields);
        }
       
        //
        $key = static::getPrimaryKey();

        //
        return $key
             ? static::loadByPrimaryKey($query, $fields)
             : static::loadByMainField($query, $fields);
    }

    /**
     *
     * @param type $index
     * @param type $fields
     * @return type
     */
    protected static function loadByPrimaryKey($index, $fields=null)
    {
        //
        $table = static::getTable();
        
        // get primary key
        $key = static::getPrimaryKey();
      
        //
        $alias = static::getClassName();

        //
        $join = null;

        //
        $requestedFields = $fields ? $fields : static::getDefaultFields();

        // parse SQL select fields
        $selectFields = static::getDatabase()
                     -> getWriter()
                     -> selectFields($requestedFields, $alias, $join);
     
        // prepare SQL query
        $sql = " SELECT {$selectFields} "
             . "   FROM {$table} AS {$alias} {$join} "
             . "  WHERE {$alias}.{$key}='{$index}' "
             . "  LIMIT 1";
        
        // fetch data on database and return it
        $result = static::fetch(
            $sql,
            null,
            true,
            is_string($fields),
            is_null($fields)
        );
             
        //
        return $result;
    }

    /**
     *
     * @param type $value
     * @param type $fields
     * @return type
     */
    protected static function loadByMainField($value, $fields=null)
    {
        //
        $table = static::getTable();

        // get main field
        $field = static::getMainField();

        //
        $alias = static::getClassName();

        //
        $join = null;

        //
        $allFields = $fields ? $fields : static::getDefaultFields();

        // parse SQL select fields
        $selectFields = static::getDatabase()
                     -> getWriter()
                     -> selectFields($allFields, $alias, $join);

        //
        $token = ':'.$field;

        //
        $values = [$token => $value];

        // prepare SQL query
        $sql = " SELECT {$selectFields}"
             . "   FROM {$table} AS {$alias} {$join}"
             . "  WHERE {$field} = {$token}"
             . "  LIMIT 1";

        // fetch data on database and return it
        $result = static::fetch(
            $sql,
            $values,
            false,
            is_string($fields)
        );
             
        //
        return $result;
    }

    /**
     *
     * @param type $query
     * @param type $fields
     * @return type
     */
    protected static function loadByQuery($query, $fields=null) {

        //
        $table = static::getTable();

        //
        $alias = static::getClassName();

        //
        $join = null;

        //
        $allFields = $fields ? $fields : static::getDefaultFields();

        // parse SQL select fields
        $selectFields = static::getDatabase()
                     -> getWriter()
                     -> selectFields($allFields, $alias, $join);

        //
        $whereConditions = array();

        //
        if (isset($query['where'])) {

            //
            $whereConditions[] = "(".$query['where'].")";

            //
            unset($query['where']);
        }

        //
        foreach ($query as $field => $value) {

            //
            $token = ':'.$field;

            //
            $whereConditions[] = "{$field} = {$token}";

            //
            $values[$field] = $value;
        }

        //
        $where = implode(' AND ', $whereConditions);

        // prepare SQL query
        $sql = "SELECT {$selectFields} "
             .   "FROM {$table} AS {$alias} {$join} "
             .  "WHERE {$where} "
             .  "LIMIT 1";

        // fetch data on database and return it
        $result = static::fetch(
            $sql,
            $values,
            true,
            is_string($fields),
            is_null($fields)
        );
        
        //
        return $result;
    }    
}