<?php

/**
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 *
 *
 *
 */
class DatabaseCommon extends Notations
{
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
     * Constant to enable debug print-out
     */
    private $_debug = 0;
	
	/**
	 * 
	 * @param type $args
	 */
	public function __construct($args) {

		// check arguments for connection
        # TODO: controls $args field for validate id

		//
		$this->_args = $args;
		
		//
        $this->_socket = new DatabaseSocketPDO();
		
		//
		$this->link = false;
	}

    /**
     * Init database connection
     *
     * @param  type                                       $host
     * @param  type                                       $username
     * @param  type                                       $password
     * @param  type                                       $database
     * @param  type                                       $prefix
     * @return \SourceForge\SchemaDB\SchemaDB_ezSQL_mysql
     */
    private function connect()
    {
		//
		if (!$this->link) 
		{	
			//
			static::log('connect', $this->_args);

			//
			$this->_socket->connect($this->_args);
			
			//
			$this->link = true;
		}
    }

    /**
     * Execute SQL query to database
     *
     * @param  type $sql
     * @return type
     */
    public function execute($sql, $values=null)
    {
		//
		$this->connect();

		//
		static::log('execute', $sql, $values);

		//
		return $this->_socket->query($sql, $values);
    }

    /**
     * Return current database prefix used
     *
     * @return type
     */
    public function getPrefix($table=null)
    {
		//
		$this->connect();

		//
		$prefix = $this->_socket->getPrefix();
				
		//
		return $table ? $prefix . $table : $prefix;
    }

    /**
     *
     * @return type
     */
    public function getLastId()
    {
		//
		$this->connect();

		//
		$id = $this->_socket->lastInsertId();

		//
		static::log('getLastId', $id);

		//
		return $id;
    }

    /**
     *
     *
     * @param  type $sql
     * @return type
     */
    public function getRow($sql, $values=null)
    {
		//
		$this->connect();
		
		//
		static::log('getRow', $sql, $values);

		//
		return $this->_socket->getRow($sql, $values);
    }

    /**
     * Get a list/array of record from database
     * based on SQL query passed
     *
     * @param  string $sql
     * @return array
     */
    public function getResults($sql)
    {
		//
		$this->connect();

		//
		static::log('getResults', $sql);

		//
		return $this->_socket->getResults($sql);
    }

    /**
     *
     * @param  type $sql
     * @return type
     */
    public function getValue($sql)
    {
		//
		$this->connect();

		//
		static::log('getValue', $sql);

		//
		return $this->_socket->getValue($sql);
    }

	 /**
     *
     * @param  type $sql
     * @return type
     */
    public function getValues($sql)
    {
		//
		$this->connect();

		//
		static::log('getValues', $sql);

		//
		return $this->_socket->getColumn($sql);
    }

	/**
	 * Test if a table exists
	 * 
	 * @param type $table
	 * @return type
	 */
	public function tableExists($table, $parse=true) {
		
		// prepare
        if ($parse) { 
			
			//
			$table = $this->getPrefix() . $table;			
		}

        //
        $escapedTable = str_replace('_', '\\_', $table);

		// sql query to test table exists
        $sql = "SHOW TABLES LIKE '{$escapedTable}'";

        // test if table exists
        $exists = $this->getRow($sql);

		// return and cast test result
		return (boolean) $exists;
	}
	
	/**
	 * Get array with current tables on database
	 * 
	 * @return array
	 */
	public function getTables() {

        // escape underscore
        $prefix = str_replace('_', '\\_', $this->getPrefix());

        //
        $sql = "SHOW TABLES LIKE '{$prefix}%'";

        //
		$tables = $this->getValues($sql);

		//
        return $tables; 
	}
			
	/**
	 * Debug mode setter
	 * 
	 */
	public function setDebug($flag) {
		
		//
		$this->_debug = (boolean) $flag;
	}
	
	/**
	 * Debug mode getter
	 * 
	 */
	protected function getDebug() {
		return $this->_debug;
	}
	
	/**
	 * 
	 * 
	 */
	private function log($method, $arg1=null, $arg2=null) {
	
		// debug the queries
        if ($this->getDebug()) {
            echo '<pre style="border:1px solid #9F6000;margin:0 0 1px 0;padding:2px 6px 3px 6px;color:#9F6000;background:#FEEFB3;">';
			echo '<strong>'.str_pad($method,12,' ',STR_PAD_LEFT).'</strong>'.($arg1?': #1 -> '.json_encode($arg1):'');
            if (isset($arg2)) {
                echo "\n".str_pad('#2 -> ',20,' ',STR_PAD_LEFT).json_encode($arg2);
            }
            echo '</pre>';
        }
	}	
}