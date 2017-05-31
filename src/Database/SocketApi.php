<?php
/**
 * Socket trait
 * comunications and interactions with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Database;

use Javanile\Moldable\Exception;

trait SocketApi
{
    /**
     * Execute SQL query to database
     *
     * @param  type $sql
     * @param  type $values
     * @return type
     */
    public function execute($sql, $values = null)
    {        
        $this->log('execute', $sql, $values);

        return $this->_socket->execute($sql, $values);
    }

    /**
     * Return current database prefix used
     *
     * @return type
     */
    public function getPrefix($table = null)
    {
        $prefix = $this->_socket->getPrefix();
        
        return $table ? $prefix . $table : $prefix;
    }

    /**
     * Get latest insert ID or UUID generated.
     *
     * @return type
     */
    public function getLastId()
    {
        $lastId = $this->_socket->lastInsertId();

        $this->log('getLastId', $lastId);

        return $lastId;
    }

    /**
     * Get a single row of a result set.
     *
     * @param  type $sql
     * @return type
     */
    public function getRow($sql, $params = null)
    {
        $this->log('getRow', $sql, $params);

        return $this->_socket->getRow($sql, $params);
    }

    /**
     * Get a list/array of record from database
     * based on SQL query passed.
     *
     * @param  string $sql
     * @return array
     */
    public function getResults($sql, $params = null)
    {
        $this->log('getResults', $sql, $params);

        return $this->_socket->getResults($sql, $params);
    }

    /**
     * Get a list/array of record from database
     * based on SQL query passed.
     *
     * @param  string $sql
     * @return array
     */
    public function getResultsAsObjects($sql, $params = null)
    {
        $this->log('getResults', $sql, $params);

        return $this->_socket->getResultsAsObjects($sql, $params);
    }

    /**
     * Get single value/first in result set.
     *
     * @param  type $sql
     * @return type
     */
    public function getValue($sql, $params = null)
    {
        $this->log('getValue', $sql, $params);

        return $this->_socket->getValue($sql, $params);
    }

    /**
     * Get all value/first for every row of result set.
     *
     * @param  string $sql
     * @param  array  $params
     * @return array
     */
    public function getValues($sql, $params = null)
    {
        $this->log('getValues', $sql, $params);

        return $this->_socket->getColumn($sql, $params);
    }

    /**
     * Test if a table exists
     *
     * @param type $table
     * @return type
     */
    public function tableExists($table, $parse = true)
    {
        // prepare
        if ($parse) { 
            $table = $this->getPrefix($table);
        }

        // escape table name for query
        $escapedTable = str_replace('_', '\\_', $table);

        // sql query to test if table exists
        $sql = "SHOW TABLES LIKE '{$escapedTable}'";

        // execute test if table exists
        $exists = $this->getRow($sql);

        return (boolean) $exists;
    }

    /**
     * Get array with current tables on database
     *
     * @return array
     */
    public function getTables()
    {
        $prefix = str_replace('_', '\\_', $this->getPrefix());
        $sql = "SHOW TABLES LIKE '{$prefix}%'";
        $tables = $this->getValues($sql);

        return $tables;
    }

    /**
     *
     *
     */
    public function quote($string)
    {
        return $this->_socket->quote($string);
    }
  
    /**
     * Log called SocketApi into log file
     *
     */
    private function log($method, $sql = null, $params = null)
    {
        /*

        if (!$this->getDebug()) {
            return;
        }

        $arg1formatted = is_string($arg1) ? str_replace([
            'SELECT ',
            ', ',
            'FROM ',
            'LEFT JOIN ',
            'WHERE ',
            'LIMIT ',
        ], [
            'SELECT ',
            "\n".'                         , ',
            "\n".'                      FROM ',
            "\n".'                 LEFT JOIN ',
            "\n".'                     WHERE ',
            "\n".'                     LIMIT ',
        ], trim($arg1)) : json_encode($arg1);

        echo '<pre style="border:1px solid #9F6000;margin:0 0 1px 0;padding:2px 6px 3px 6px;color:#9F6000;background:#FEEFB3;">';
        echo '<strong>'.str_pad($method,12,' ',STR_PAD_LEFT).'</strong>'.($arg1?': #1 -> '.$arg1formatted:'');
        if (isset($arg2)) {
            echo "\n".str_pad('#2 -> ',20,' ',STR_PAD_LEFT).json_encode($arg2);
        }
        echo '</pre>';
        */
    }
}
