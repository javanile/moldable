<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Database implements Notations
{
    use Database\ModelApi;
    use Database\ErrorApi;
    use Database\CacheApi;
    use Database\SocketApi;
    use Database\SchemaApi;
    use Database\FieldApi;
    use Database\InsertApi;
    use Database\UpdateApi;
    use Database\RawApi;

    /**
     * Release version number.
     *
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * Constructor arguments passed.
     *
     * @var array
     */
    private $_args = null;

    /**
     * Connected for database socket.
     *
     * @var type
     */
    private $_socket = null;

    /**
     * Connected for database sql writer or composer.
     *
     * @var object
     */
    private $_writer = null;

    /**
     * Connected for database schema parser or interpreter.
     *
     * @var object
     */
    private $_parser = null;

    /**
     * Logger handler.
     *
     * @var object
     */
    private $_logger = null;

    /**
     * Database status ready for queries.
     *
     * @var bool
     */
    private $_ready = null;

    /**
     * Trace for debugging.
     *
     * @var object
     */
    private $_trace = null;

    /**
     * Timestamp for benchmark.
     *
     * @var float
     */
    private $_ts = null;

    /**
     * Constant to enable debug print-out.
     *
     * @var bool
     */
    private $_debug = false;

    /**
     * Database instance for singleton or default implicit call.
     *
     * @var object
     */
    protected static $_default = null;

    /**
     * Construct and connect a SchemaDB drive
     * to mysql database best way to start use it.
     *
     * @param array $args Array with connection parameters
     */
    public function __construct($args)
    {
        $this->_ts = microtime();
        $this->_args = $args;
        $this->_trace = debug_backtrace();
        $this->_ready = false;

        $socket = isset($args['socket']) ? ucfirst(strtolower($args['socket'])) : 'Pdo';
        $socketClass = "\\Javanile\\Moldable\\Database\\Socket\\{$socket}Socket";

        if (!class_exists($socketClass)) {
            $this->error('connect', "socket class '{$socketClass}' not found");
        }

        $this->_socket = new $socketClass($this, $args);
        $this->_parser = new Parser\MysqlParser();
        $this->_writer = new Writer\MysqlWriter();

        if (isset($args['debug']) && $args['debug']) {
            $this->setDebug($args['debug']);
        }

        // Logger
        $logFile = isset($args['log']) ? $args['log'] : getcwd().'/moldable.log';
        $logFlag = isset($args['debug']) && $args['debug'] ? Logger::INFO : Logger::ERROR;
        $this->_logger = new Logger('name');
        $this->_logger->pushHandler(new StreamHandler($logFile, $logFlag));

        // Set as default database
        static::setDefault($this);
    }

    /**
     * Retrieve default SchemaDB connection.
     *
     * @return type
     */
    public static function getDefault()
    {
        if (static::$_default != null) {
            return static::$_default;
        }

        // check if running into laravel context
        if (Context::checkLaravel()) {
            static::$_default = new self(['socket' => 'Laravel']);

            return static::$_default;
        }

        // check if registered container
        if (Context::checkContainer()) {
            return Context::getContainerDatabase();
        }
    }

    /**
     * Test if have a default db connection.
     *
     * @return type
     */
    public static function hasDefault()
    {
        return static::getDefault() !== null;
    }

    /**
     * Set global context default database
     * for future use into model management.
     *
     * @param type $database
     */
    public static function setDefault($database)
    {
        // if no default SchemaDB connection auto-set then-self
        if (static::$_default === null) {
            // set current SchemaDB connection to default
            static::$_default = &$database;
        }
    }

    /**
     * Set global context default database
     * for future use into model management.
     *
     * @param type $database
     */
    public static function resetDefault()
    {
        static::$_default = null;
    }

    /**
     * Retrieve current parser.
     *
     * @return object Current parser
     */
    public function getParser()
    {
        return $this->_parser;
    }

    /**
     * Retrieve current writer.
     *
     * @return object Current writer
     */
    public function getWriter()
    {
        return $this->_writer;
    }

    /**
     * Debug mode setter.
     *
     * @param bool $flag Set True to enable debug mode.
     */
    public function setDebug($flag)
    {
        $this->_debug = (bool) $flag;
    }

    /**
     * Debug mode getter.
     *
     * @return bool Return debug mode status.
     */
    public function isDebug()
    {
        return (bool) $this->_debug;
    }

    /**
     * Print-out a memory used benchmark.
     *
     * @return float Time elapsed.
     */
    public function benchmark()
    {
        return [
            'start'  => $this->_ts,
            'elapse' => microtime() - $this->_ts,
            'memory' => memory_get_usage(true),
        ];
    }
}
