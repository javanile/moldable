<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 *
 *
 *
 */
class DatabaseCommon
{
	/**
	 * 
	 */
	private $args = null;

	/**
     *
     * @var type
     */
    private $sock = null;

	/**
	 * 
	 * @param type $args
	 */
	public function __construct($args) {

		## check arguments for connection
        # TODO: controls $args field for validate id

		##
		$this->args = $args;
		
		##
        $this->sock = new DatabaseSocketPDO();
		
		##
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
		##
		if (!$this->link) 
		{	
			##
			static::log('connect', $this->args);

			##
			$this->sock->connect($this->args);
			
			##
			$this->link = true;
		}
    }

    /**
     * Execute SQL query to database
     *
     * @param  type $sql
     * @return type
     */
    public function query($sql)
    {
		##
		$this->connect();

		##
		static::log('query', $sql);

		##
		return $this->sock->query($sql);
    }

    /**
     * Return current database prefix used
     *
     * @return type
     */
    public function getPrefix()
    {
		##
		$this->connect();

		##
		$perfix = $this->sock->getPrefix();
		
		##
		static::log('getPrefix', $perfix);
		
		##
		return $perfix;
    }

    /**
     *
     * @return type
     */
    public function getLastId()
    {
		##
		$this->connect();

		##
		$id = $this->sock->lastInsertId();

		##
		static::log('getLastId', $id);

		##
		return $id;
    }

    /**
     *
     *
     * @param  type $sql
     * @return type
     */
    public function getRow($sql)
    {
		##
		$this->connect();
		
		##
		static::log('getRow', $sql);

		##
		return $this->sock->getRow($sql);
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
		##
		$this->connect();

		##
		static::log('getResults', $sql);

		##
		return $this->sock->getResults($sql);
    }

    /**
     *
     * @param  type $sql
     * @return type
     */
    public function getValue($sql)
    {
		##
		$this->connect();

		##
		static::log('getValue', $sql);

		##
		return $this->sock->getVar($sql);
    }

	 /**
     *
     * @param  type $sql
     * @return type
     */
    public function getValues($sql)
    {
		##
		$this->connect();

		##
		static::log('getValues', $sql);

		##
		return $this->sock->getColumn($sql);
    }

	/**
	 * Test if a table exists
	 * 
	 * @param type $table
	 * @return type
	 */
	public function tableExists($table) {
		
		## sql query to test table exists
        $sql = "SHOW TABLES LIKE '{$table}'";

        ## test if table exists
        $exists = $this->getRow($sql);

		## return and cast test result
		return (boolean) $exists;
	}
	
	/**
	 * Get array with current tables on database
	 * 
	 * @return array
	 */
	public function getTables() {
	
		##
        $prefix = $this->getPrefix();

        ##
        $sql = "SHOW TABLES LIKE '{$prefix}%'";

        ##
		$tables = $this->getValues($sql);
		
		##
        return $tables; 
	}
	
	/**
	 * 
	 * 
	 */
	public static function log($method, $args=null) {
	
		## debug the queries
        if (static::DEBUG) {
            echo '<pre style="border:1px solid #9F6000;margin:0 0 1px 0;padding:2px 6px 3px 6px;color:#9F6000;background:#FEEFB3;">';
			echo '<strong>'.str_pad($method,14,' ',STR_PAD_LEFT).'</strong>'.($args?': '.json_encode($args):'').'</pre>';
        }
	}	
}