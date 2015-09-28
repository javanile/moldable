<?php

/*\
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * 
 * 
 */
class SocketPDO {
	
	/**
	 *
	 * @var type 
	 */
	private $dbo = null; 

	/**
	 *
	 * @var type 
	 */
	private $prefix = null;
	
	/**
	 * 
	 * @param type $args
	 */
	public function __construct($args) {
		
		##
		$dsn = "mysql:host={$args['host']};dbname={$args['name']}";
		
		##
		$opt = array(
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		); 

		##
		$this->dbo = new \PDO($dsn,$args['user'],$args['pass'],$opt);		
	
		##
		$this->prefix = $args['pref'];		
	}
	
	/**
	 * 
	 * @param type $sql
	 */
	public function query($sql) {
			 	
		##
		$this->dbo->query($sql);		
	}

	/**
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getRow($sql) {
		
		##
		$s = $this->dbo->prepare($sql);
		
		##
		$s->execute();

		##
		return $s->fetch();
	}
	
	/**
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getResults($sql) {
		
		##
		$s = $this->dbo->prepare($sql);
		
		##
		$s->execute();

		##
		return $s->fetchAll();
	}
	
	/**
	 * 
	 * @return type
	 */
	public function getPrefix()
	{	
		##
		return $this->prefix;		
	}
	
	/**
	 * 
	 * 
	 */
	public function lastInsertId() 
	{
		##
		return $this->dbo->lastInsertId();		
	}	
}