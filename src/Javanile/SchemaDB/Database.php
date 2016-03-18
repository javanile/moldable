<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB;

use Javanile\SchemaDB\Database\Socket;

class Database implements Notations
{
    use Database\ModelApi;
    use Database\ErrorApi;
    use Database\SocketApi;
    use Database\SchemaApi;

    /**
     * Currenti release version number
     */
    const VERSION = '0.3.0';

    /**
	 *
	 */
	private $_args = null;

	/**
     *
     * @var type
     */
    private $_socket = null;

    /**
     *
     * @var type
     */
    private $_writer = null;

    /**
     *
     * @var type
     */
    private $_parser = null;

    /**
     *
     * @var type
     */
    private $_ready = null;

	/**
     * Constant to enable debug print-out
     *
     * @var boolean
     */
    private $_debug = false;

    /**
	 * Timestamp for benchmark
	 */
	private $_trace = null;

	/**
	 * Timestamp for benchmark
	 */
	private $_ts = null;
		
    /**
     *
     *
     * @var type
     */
    protected static $_defaultDatabase = null;

    /**
     * Construct and connect a SchemaDB drive
     * to mysql database best way to start use it
     *
     * @param array $args Array with connection parameters
     */
    public function __construct($args)
    {
        //
		$this->_ts = microtime();

        //
        $this->_trace = debug_backtrace();
        
        // check arguments for connection
        foreach(['host','dbname','username'] as $attr) {
            if (!isset($args[$attr])) {
                $this->errorConnect("Required attribute: '{$attr}'");
            }
        }

        //
		$this->_args = $args;

		//
        $this->_socket = new Socket($this->_args);

        //
        $this->_parser = new Parser\Mysql();

        //
        $this->_writer = new Writer\Mysql();

		//
		$this->_ready = false;
        
        //
        static::setDefault($this);
    }
    
    /**
     * Retrieve default SchemaDB connection
     *
     * @return type
     */
    public static function getDefault()
    {
        // return static $default
        return static::$_defaultDatabase;
    }

    /**
     * Set global context default database 
	 * for future use into model management
	 * 
     * @param type $database
     */
    public static function setDefault($database)
    {
        // if no default SchemaDB connection auto-set then-self
        if (static::$_defaultDatabase === null) {

            // set current SchemaDB connection to default
            static::$_defaultDatabase = &$database;
        }
    }

    /**
     *
     */
    public function isReady()
    {
        //
        if (!$this->_ready) {
            $this->enquire();
        }

        //
        return $this->_ready;
    }

    /**
     *
     *
     */
    public function getParser()
    {
        //
        return $this->_parser;
    }

    /**
     *
     *
     */
    public function getWriter()
    {
        //
        return $this->_writer;
    }

	/**
	 *
     *
	 */
	public function benchmark() {

        //
        $style = 'background:#333;'
               . 'color:#fff;'
               . 'padding:2px 6px 3px 6px;'
               . 'border:1px solid #000';

        //
        $infoline = 'Time: '.(microtime()-$this->_ts).' '
                  . 'Mem: '.memory_get_usage(true);

		// 
		echo '<pre style="'.$style.'">'.$infoline.'</pre>';
	}
}


