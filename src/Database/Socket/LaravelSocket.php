<?php
/**
 * PDO Socket handle comunications and interactions
 * with database via PDO library.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database\Socket;

use Illuminate\Database\Capsule\Manager as Capsule;

class LaravelSocket
{
    /**
     * @var \Javanile\SchemaDB\Database
     */
    private $_database = null;

    /**
     * @var int
     */
    private $_connection = null;

    /**
     * @var int
     */
    private $_pdo = null;

    /**
     * @var int
     */
    private $_socket = null;

    /**
     * @var int
     */
    private $_args = null;

    /**
     *
     */
    private $_prefix = null;

    /**
     * Construct socket.
     *
     * @param mixed      $database
     * @param null|mixed $args
     */
    public function __construct($database, $args = null)
    {
        $this->_database = $database;
        $this->_connection = Capsule::connection();
        $this->_pdo = $this->_connection->getPdo();
        $this->_socket = new PdoSocket($this->_database, ['pdo' => $this->_pdo]);
        $this->_args = $args;
        $this->_prefix = $this->_connection->getTablePrefix();
    }

    /**
     * Get a single row.
     *
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getRow($sql, $params = null)
    {
        return $this->_socket->getRow($sql, $params = null);
    }

    /**
     * Get list of records.
     *
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getResults($sql, $params = null)
    {
        return $this->_socket->getResults($sql, $params = null);
    }

    /**
     * Get list of records as object.
     *
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getResultsAsObjects($sql, $params = null)
    {
        return $this->_socket->getResultsAsObjects($sql, $params);
    }

    /**
     * Get a array of values of specific column.
     *
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getColumn($sql, $params = null)
    {
        return $this->_socket->getColumn($sql, $params);
    }

    /**
     * Get a single value.
     *
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getValue($sql, $params = null)
    {
        return $this->_socket->getValue($sql, $params);
    }

    /**
     * Return prefix passed on init attribute.
     *
     * @param mixed $table
     *
     * @return type
     */
    public function getPrefix($table = '')
    {
        return $this->_prefix.$table;
    }

    /**
     * Return prefix passed on init attribute.
     *
     * @param mixed $prefix
     *
     * @return type
     */
    public function setPrefix($prefix)
    {
        //DB::setTablePrefix($prefix);
        $this->_prefix = $prefix;
    }

    /**
     * Return last insert id.
     *
     * @return type
     */
    public function lastInsertId()
    {
        return $this->_socket->lastInsertId();
    }

    /**
     * Return last insert id.
     *
     * @param mixed $string
     *
     * @return type
     */
    public function quote($string)
    {
        return $this->_socket->quote($string);
    }

    /**
     * Start transact query.
     */
    public function transact()
    {
        return $this->_socket->transact();
    }

    public function commit()
    {
        return $this->_socket->commit();
    }

    /**
     * Roll back query.
     */
    public function rollback()
    {
        return $this->_socket->rollBack();
    }

    /**
     * Execute query.
     *
     * @param mixed      $sql
     * @param null|mixed $params
     */
    public function execute($sql, $params = null)
    {
        return $this->_socket->execute($sql, $params);
    }
}
