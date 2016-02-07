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
    public static function load($index, $fields=null)
    {
        // update table schema on DB
        static::applyTable();

        //
		try {
            
            //
            if (is_array($index)) {
                return static::loadByQuery($index, $fields);
            }

            //
            $key = static::getPrimaryKey();

            //
            if ($key) {
                return static::loadByPrimaryKey($index, $fields);
            }

            //
            else {
                return static::loadByMainField($index, $fields);
            }
        }

		//
		catch (DatabaseException $ex) {
            
            //
			static::error(debug_backtrace(), $ex);
		}
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
        $allFields = $fields ? $fields : static::getDefaultFields();

        // parse SQL select fields
        $selectFields = static::getDatabase()
                     -> getComposer()
                     -> selectFields($allFields, $alias, $join);
     
        // prepare SQL query
        $sql = " SELECT {$selectFields} "
             . "   FROM {$table} AS {$alias} {$join} "
             . "  WHERE {$alias}.{$key}='{$index}' "
             . "  LIMIT 1";

        // fetch data on database and return it
        return static::fetch(
            $sql,
            null,
            false,
            is_string($fields),
            is_null($fields)
        );
    }

    /**
     *
     * @param type $value
     * @param type $fields
     * @return type
     */
    protected static function loadByMainField($value, $fields=null) {

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
        $selectFields = MysqlComposer::selectFields($allFields, $alias, $join);

        // prepare SQL query
        $sql = " SELECT {$selectFields}"
             . "   FROM {$table} AS {$alias} {$join} "
             . "  WHERE {$field} = '{$value}' "
             . "  LIMIT 1";

        // fetch data on database and return it
        return static::fetch($sql, $values, false, is_string($fields));
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
        $selectFields = MysqlComposer::selectFields($allFields, $alias, $join);

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
        return static::fetch($sql, $values, false, is_string($fields), is_null($fields));
    }

    /**
     *
     *
     * @param type $trace
     * @param type $error
     */
    public static function error($trace, $error) {


        echo '<br>'
           . '<b>Fatal error</b>: '
           . $error->getMessage().' in method <strong>'.$trace[0]['function'].'</strong> '
           . 'called at <strong>'.$trace[0]['file'].'</strong> on line <strong>'
           . $trace[0]['line'].'</strong>'."<br>";
        die();
    }
}