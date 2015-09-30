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
	 */
	public function connect($args) {
		##
		$dsn = "mysql:host={$args['host']};dbname={$args['name']}";
		
		##
		$opt = array(
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		); 

		##
		$this->dbo = new \PDO($dsn,$args['user'],$args['pass'],$opt);		
	
		##
		$this->dbo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		
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
		$statament = $this->dbo->prepare($sql);
		
		##
		$statament->execute();

		##
		$all = $statament->fetchAll(\PDO::FETCH_ASSOC);
				
		##
		return $all;
	}
	
	/**
	 * Return prefix passed on init attribute
	 * 
	 * @return type
	 */
	public function getPrefix()
	{	
		##
		return $this->prefix;		
	}
	
	/**
	 * Return last insert id 
	 * 
	 * @return type
	 */
	public function lastInsertId() 
	{
		##
		return $this->dbo->lastInsertId();		
	}
	
	/**
	 * 
	 * 
	 */
	public function transact() {
		
		##
		$this->dbo->beginTransaction();		
	}
	
	/**
	 * 
	 */
	public function commit() {

		##
		$this->dbo->commit();				
	}
	
	/**
	 * 
	 */
	public function rollback() {

		##
		$this->dbo->rollBack();				
	}
}