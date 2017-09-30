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

class PdoSocket implements SocketInterface
{
    /**
     * @var type
     */
    private $_pdo = null;

    /**
     * @var type
     */
    private $_args = null;

    /**
     * @var type
     */
    private $_prefix = null;

    /**
     * @var type
     */
    private $_database = null;

    /**
     * @
     *
     * @param mixed $database
     * @param mixed $args
     */
    public function __construct($database, $args)
    {
        if (!$args || !is_array($args)) {
            $database->errorConnect('required connection arguments');
        }

        // init params
        $this->_args = $args;
        $this->_prefix = isset($args['prefix']) ? $args['prefix'] : '';
        $this->_database = $database;

        // set custom pdo connection
        if (isset($args['pdo']) && $args['pdo']) {
            $this->_pdo = $args['pdo'];

            return;
        }

        // check for required connection params
        foreach (['host', 'dbname', 'username'] as $attr) {
            if (!isset($args[$attr])) {
                $this->_database->errorConnect("required connection attribute '{$attr}'");
            }
        }

        //
        if (isset($args['charset'])) {
            // TODO: apply charset
        }

        // create and start pdo
        $this->connect();
    }

    /**
     * Start PDO connection.
     */
    private function connect()
    {
        $port = isset($this->_args['port']) && $this->_args['port'] ? $this->_args['port'] : 3306;
        $dsn = "mysql:host={$this->_args['host']};port={$port};dbname={$this->_args['dbname']}";
        $username = $this->_args['username'];
        $password = $this->_args['password'];

        // TODO: Move to use the follow: PDO::ATTR_EMULATE_PREPARES to 'false'
        // For security reason
        $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];

        /*\
         * The latter can be fixed by turning off emulation
         * and using the DSN charset attribute instead of SET NAMES.
         * The former is tricky, because you have no concept for safely
         * dealing with identifiers (they're just dumped straight into the query).
         * One possible approach would be to wrap all identifiers in backticks
         * while escaping all backticks within the identifiers (through doubling).
         * Whether this actually works in all cases is an open question, though.
        \*/

        try {
            $this->_pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $ex) {
            $this->_database->errorConnect($ex);
        }

        $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getRow($sql, $params = null)
    {
        $stmt = $this->execute($sql, $params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getResults($sql, $params = null)
    {
        $stmt = $this->execute($sql, $params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getResultsAsObjects($sql, $params = null)
    {
        $stmt = $this->execute($sql, $params);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get single column elements.
     *
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getColumn($sql, $params = null)
    {
        $stmt = $this->execute($sql, $params);
        $column = [];

        while ($row = $stmt->fetch()) {
            $column[] = $row[0];
        }

        return $column;
    }

    /**
     * @param type       $sql
     * @param null|mixed $params
     *
     * @return type
     */
    public function getValue($sql, $params = null)
    {
        $stmt = $this->execute($sql, $params);

        return $stmt->fetchColumn(0);
    }

    /**
     * Return prefix passed on init attribute.
     *
     * @return type
     */
    public function getPrefix()
    {
        return $this->_prefix;
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
        $this->_prefix = $prefix;
    }

    /**
     * Return last insert id.
     *
     * @return type
     */
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
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
        return $this->_pdo->quote($string);
    }

    /**
     * Starting a transaction to DB.
     */
    public function transact()
    {
        $this->_pdo->beginTransaction();
    }

    /**
     * Transactional commit.
     */
    public function commit()
    {
        $this->_pdo->commit();
    }

    /**
     * Transactional rollback.
     */
    public function rollback()
    {
        $this->_pdo->rollBack();
    }

    /**
     * Execute a SQL query on DB with binded values.
     *
     * @param mixed      $sql
     * @param null|mixed $params
     */
    public function execute($sql, $params = null)
    {
        $stmt = $this->_pdo->prepare($sql);

        if (is_array($params)) {
            foreach ($params as $token => $value) {
                $stmt->bindValue($token, $value);
            }
        }

        try {
            $stmt->execute();
        } catch (PDOException $exception) {
            $this->_database->errorExecute($exception);
        }

        return $stmt;
    }
}
