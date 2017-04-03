<?php
/**
 *
 *
 *
 */

namespace Javanile\Moldable\Database\Socket;

use PDO;
use PDOException;
use Javanile\SchemaDB\Exception;

class PdoSocket
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
     * 
     */
    public function __construct($database, $args=null)
    {
        // check arguments for connection
        foreach (['host', 'dbname', 'username'] as $attr) {
            if (!isset($args[$attr])) {
                $database->errorConnect("Required attribute: '{$attr}'");
            }
        }

        //
        if ($args != null) {

            //
            $this->_args = $args;

            //
            $this->_prefix = $args['prefix'];
        }
    }

    /**
     *
     * 
     */
    public function connect($args=null)
    {
        //
        if ($args != null) {

            //
            $this->_args = $args;

            //
            $this->_prefix = $args['prefix'];
        }

        //
        $dsn = "mysql:host={$this->_args['host']};dbname={$this->_args['dbname']}";

        //
        $opt = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        //
        try {
            $this->_pdo = new PDO(
                $dsn,
                $this->_args['username'],
                $this->_args['password'],
                $opt
            );
        }

        // wrap PDOException with SDBException
        catch (PDOException $ex) {
            throw new Exception(
                $ex->getMessage(),
                intval($ex->getCode())
            );
        }

        //
        $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //
        return true;
    }
    
    /**
     *
     * @param type $sql
     * @return type
     */
    public function getRow($sql, $params=null)
    {
        //
        $stmt = $this->execute($sql, $params);

        //
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