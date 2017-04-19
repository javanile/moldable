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
use PDOException;
use Javanile\Moldable\Exception;

class PdoSocket implements SocketInterface
{	 
    /**
     *
     * @var type
     */
    private $_pdo = null;

    /**
     * 
     *
     */
    private $_args = null;

    /**
     *
     * @var type
     */
    private $_prefix = null;

    /**
     *
     * @
     */
    public function __construct($database, $args)
    {
        //
        if (!$args || !is_array($args)) {
            $message = "required connection arguments.";
            $database->errorConnect($message);
        }

        //
        foreach (['host', 'dbname', 'username'] as $attr) {
            if (!isset($args[$attr])) {
                $message = "required connection attribute '{$attr}'";
                $database->errorConnect($message);
            }
        }

        //
        $this->_args = $args;
        $this->_prefix = isset($args['prefix']) ? $args['prefix'] : '';

        //
        $this->connect();
    }

    /**
     * Start PDO connection.
     */
    private function connect()
    {
        // get connection arguments
        $dsn = "mysql:host={$this->_args['host']};dbname={$this->_args['dbname']}";
        $username = $this->_args['username'];
        $password = $this->_args['password'];
        $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];

        // try to connect and create singletone
        try {
            $this->_pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage(), intval($ex->getCode()));
        }

        // set PDO attributes
        $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     *
     * @param type $sql
     * @return type
     */
    public function getRow($sql, $params=null)
    {
        $stmt = $this->execute($sql, $params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     *
     * @param type $sql
     * @return type
     */
    public function getResults($sql, $params=null)
    {
        //
        $stmt = $this->execute($sql, $params);

        //
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *
     * @param type $sql
     * @return type
     */
    public function getResultsAsObjects($sql, $params=null)
    {
        //
        $stmt = $this->execute($sql, $params);

        //
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     *
     *
     * @param type $sql
     * @return type
     */
    public function getColumn($sql, $values=null)
    {
        //
        $stmt = $this->execute($sql, $values);

        //
        $column = array();

        //
        while($row = $stmt->fetch()){
            $column[] = $row[0];
        }

        //
        return $column;
    }

    /**
     *
     *
     * @param type $sql
     * @return type
     */
    public function getValue($sql, $values=null)
    {
        //
        $stmt = $this->execute($sql, $values);

        //
        return $stmt->fetchColumn(0);
    }

    /**
     * Return prefix passed on init attribute
     *
     * @return type
     */
    public function getPrefix()
    {
        //
        return $this->_prefix;
    }

    /**
     * Return prefix passed on init attribute
     *
     * @return type
     */
    public function setPrefix($prefix)
    {
        //
        $this->_prefix = $prefix;
    }

    /**
     * Return last insert id
     *
     * @return type
     */
    public function lastInsertId()
    {
        //
        return $this->_pdo->lastInsertId();
    }

    /**
     * Return last insert id
     *
     * @return type
     */
    public function quote($string)
    {
        //
        return $this->_pdo->quote($string);
    }

    /**
     *
     *
     */
    public function transact() {

        //
        $this->_pdo->beginTransaction();
    }

    /**
     *
     */
    public function commit()
    {
        //
        $this->_pdo->commit();
    }

    /**
     *
     */
    public function rollback()
    {
        //
        $this->_pdo->rollBack();
    }

    /**
     *
     * 
     */
    public function execute($sql, $values=null)
    {
        //
        $stmt = $this->_pdo->prepare($sql);

        //
        if (is_array($values)) {
            foreach($values as $token => $value) {
                $stmt->bindValue($token, $value);
            }
        }

        //
        try {
            $stmt->execute();
        }

        // wrap PDOException with SDBException
        catch (PDOException $ex) {
            throw new Exception(
                $ex->getMessage(),
                intval($ex->getCode())
            );
        }

        //
        return $stmt;
    }
}