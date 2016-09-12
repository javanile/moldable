<?php
/**
 *
 *
 *
 */

namespace Javanile\SchemaDB\Socket;

use DB;
use PDO;
use Javanile\SchemaDB\Exception;

class LaravelSocket
{
    /**
     * @var \Javanile\SchemaDB\Database
     */
    private $_database = null;

    /**
     * @var int
     */
    private $_tempFetchMode = null;
    
    /**
     *
     * 
     */
    public function __construct($database, $args=null)
    {
        //
        $this->_database = $database;
    }

    /**
     *
     * 
     */
    public function connect($args=null)
    {
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
        $this->applyFetchMode(PDO::FETCH_ASSOC);

        //
        if ($params) {
            $row = DB::selectOne($sql, $params);
        } else {
            $row = DB::selectOne($sql);
        }

        //
        $this->resetFetchMode();

        //
        return $row;
    }

    /**
     *
     * @param type $sql
     * @return type
     */
    public function getResults($sql, $params=null)
    {
        //
        $this->applyFetchMode(PDO::FETCH_ASSOC);

        //
        if ($params) {
            $results = DB::select($sql, $params);
        } else {
            $results = DB::select($sql);
        }

        var_Dump($results);
        
        //
        $this->resetFetchMode();

        //
        return $results;
    }

    /**
     *
     * @param type $sql
     * @return type
     */
    public function getResultsAsObjects($sql, $params=null)
    {
        //
        $this->applyFetchMode(PDO::FETCH_OBJ);

        //
        if ($params) {
            $results = DB::select($sql, $params);
        } else {
            $results = DB::select($sql);
        }

        //
        $this->resetFetchMode();

        //
        return $results;
    }

    /**
     *
     *
     * @param type $sql
     * @return type
     */
    public function getColumn($sql, $params=null)
    {
        //
        $stmt = $this->execute($sql, $params);

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
    public function getValue($sql, $params=null)
    {
        //
        $stmt = $this->execute($sql, $params);

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
        return DB::getTablePrefix();
    }

    /**
     * Return prefix passed on init attribute
     *
     * @return type
     */
    public function setPrefix($prefix)
    {
        //
        DB::setTablePrefix($prefix);
    }

    /**
     * Return last insert id
     *
     * @return type
     */
    public function lastInsertId()
    {
        //
        return DB::getPdo()->lastInsertId();
    }

    /**
     * Return last insert id
     *
     * @return type
     */
    public function quote($string)
    {
        //
        return DB::getPdo()->quote($string);
    }

    /**
     *
     *
     */
    public function transact()
    {
        //
        DB::getPdo()->beginTransaction();
    }

    /**
     *
     */
    public function commit()
    {
        //
        DB::getPdo()->commit();
    }

    /**
     *
     */
    public function rollback()
    {
        //
        DB::getPdo()->rollBack();
    }

    /**
     *
     * 
     */
    public function execute($sql, $params=null)
    {
        //
        if ($params) {
            return DB::statement($sql, $params);
        } else {
            return DB::statement($sql);
        }
    }

    /**
     *
     *
     */
    private function applyFetchMode($fetchMode)
    {
        //
        if ($this->_tempFetchMode != null) {
            $this->_database->errorExecute(new Exception("apply fetchMode overload"));
        }

        //
        $this->_tempFetchMode = DB::getFetchMode();

        //
        DB::setFetchMode($fetchMode);
    }

    /**
     *
     *
     */
    private function resetFetchMode()
    {
        //
        DB::setFetchMode($this->_tempFetchMode);

        //
        $this->_tempFetchMode = null;
    }
}