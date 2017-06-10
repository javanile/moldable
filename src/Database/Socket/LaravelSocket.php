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

use PDO;
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
     * 
     */
    public function __construct($database, $args = null)
    {
        $this->_database   = $database;
        $this->_connection = Capsule::connection();
        $this->_pdo        = $this->_connection->getPdo();
        $this->_socket     = new PdoSocket($this->_database, ['pdo' => $this->_pdo]);
        $this->_args       = $args;
    }

    /**
     *
     * @param type $sql
     * @return type
     */
    public function getRow($sql, $params = null)
    {
        return $this->_socket->getRow($sql, $params = null);
    }

    /**
     *
     * @param type $sql
     * @return type
     */
    public function getResults($sql, $params = null)
    {
        return $this->_socket->getResults($sql, $params = null);
    }

    /**
     *
     * @param type $sql
     * @return type
     */
    public function getResultsAsObjects($sql, $params = null)
    {
        return $this->_socket->getResultsAsObjects($sql, $params);
    }

    /**
     *
     *
     * @param type $sql
     * @return type
     */
    public function getColumn($sql, $params = null)
    {
        return $this->_socket->getColumn($sql, $params);
    }

    /**
     *
     *
     * @param type $sql
     * @return type
     */
    public function getValue($sql, $params = null)
    {
        return $this->_socket->getValue($sql, $params);
    }

    /**
     * Return prefix passed on init attribute
     *
     * @return type
     */
    public function getPrefix($table = "")
    {
        return $this->_connection->getTablePrefix() . $table;
    }

    /**
     * Return prefix passed on init attribute
     *
     * @return type
     */
    public function setPrefix($prefix)
    {
        //DB::setTablePrefix($prefix);
        return $prefix;
    }

    /**
     * Return last insert id
     *
     * @return type
     */
    public function lastInsertId()
    {
        return $this->_socket->lastInsertId();
    }

    /**
     * Return last insert id
     *
     * @return type
     */
    public function quote($string)
    {
        return $this->_socket->quote($string);
    }

    /**
     *
     *
     */
    public function transact()
    {
        return $this->_socket->transact();
    }

    /**
     *
     */
    public function commit()
    {
        return $this->_socket->commit();
    }

    /**
     *
     */
    public function rollback()
    {
        return $this->_socket->rollBack();
    }

    /**
     *
     * 
     */
    public function execute($sql, $params = null)
    {
        return $this->_socket->execute($sql, $params);
    }
}
