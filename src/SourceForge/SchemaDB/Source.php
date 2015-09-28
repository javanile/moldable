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
class Source
{
    /**
     * Constant to handle database interaction (execute)
     */
    const QUERY = 0;
    const CONNECT = 1;
    const GET_ROW = 2;
    const GET_VALUE = 3;
    const GET_PREFIX = 4;
    const GET_LAST_ID = 5;
    const GET_RESULTS = 6;

    /**
     *
     * @var type
     */
    private $db = null;

	/**
	 * 
	 * @param type $args
	 */
	public function __construct($args) {

		##
        $this->connect($args);
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
    protected function connect($args)
    {
        ## check arguments for connection
        # TODO: controls $args field for validate id

        ## execute connection
        $this->execute(static::CONNECT, $args);
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
        return $this->execute(static::QUERY, $sql);
    }

    /**
     * Return current database prefix used
     *
     * @return type
     */
    public function getPrefix()
    {
        ##
        return $this->execute(static::GET_PREFIX);
    }

    /**
     *
     * @return type
     */
    public function getLastId()
    {
        ##
        return $this->execute(static::GET_LAST_ID);
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
        return $this->execute(static::GET_ROW, $sql);
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
        ## e
        return $this->execute(static::GET_RESULTS, $sql);
    }

    /**
     *
     * @param  type $sql
     * @return type
     */
    public function getValue($sql)
    {
        ## e
        return $this->execute(static::GET_VALUE, $sql);
    }

    /**
     *
     * @param  type         $method
     * @param  array/string $args
     * @return type
     */
    public function execute($method, $args=null)
    {        
        ## select appropriate method
        switch ($method) {

            ##
            case static::CONNECT:
				
				##
				static::log('CONNECT', $args);
                
				##
                $this->db = new SocketPDO($args);
                
                ##
                return true;  

            ##
            case static::QUERY:	
				
				##
				static::log('QUERY', $args);
                
				##
				return $this->db->query($args);
			
            ##
            case static::GET_ROW: 
				
				##
				static::log('GET_ROW', $args);
                
				##
				return $this->db->getRow($args);
			
            ##
            case static::GET_VALUE: 
				
				##
				static::log('GET_VALUE', $args);
                
				##
				return $this->db->getVar($args);

            ##
            case static::GET_PREFIX: 
				$perfix = $this->db->getPrefix();
				static::log('GET_PREFIX', $perfix);
				return $perfix;

            ##
            case static::GET_LAST_ID: 
				
				$id = $this->db->lastInsertId();
				
				static::log('GET_LAST_ID', $id);
				
				return $id;

            ##
            case static::GET_RESULTS: 
				
				static::log('GET_RESULTS', $args);
				
				return $this->db->getResults($args);

            ##
            default: die("execute method not exists");
        }
    }

	/**
	 * 
	 * 
	 */
	public static function log($method, $args=null) {
	
		## debug the queries
        if (static::DEBUG) {
            echo '<pre style="border:1px solid #9F6000;margin:0 0 1px 0;padding:2px;color:#9F6000;background:#FEEFB3;"><strong>'.str_pad($method,14,' ',STR_PAD_LEFT).'</strong>'.($args?': '.json_encode($args):'').'</pre>';
        }
	}
	
    /**
     * printout database status and info
     */
    public function dump()
    {
        ## describe databse
        $s = $this->desc();

        ##
        echo '<pre><table border="1" style="text-align:center">';

        ##
        if ($s) {

            ##
            foreach ($s as $t => $d) {

                ##
                echo '<tr><th colspan="9">'.$t.'</th></tr>';
                echo '<tr><td>&nbsp;</td>';
                $r = key($d);
                foreach ($d[$r] as $k=>$v) {
                    echo '<th>'.$k.'</th>';
                }
                echo '</tr>';
                foreach ($d as $f => $a) {
                    echo '<tr>';
                    echo '<th>'.$f.'</th>';
                    foreach ($a as $k=>$v) {
                        echo '<td>'.$v.'</td>';
                    }
                    echo '</tr>';
                }
            }
        } else {
            echo '<tr><th>No database tables</th></tr>';
        }

        ##
        echo '</table></pre>';
    }
}