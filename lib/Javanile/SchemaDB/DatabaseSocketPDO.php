<?php

/*\
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * 
 */
use PDO;

/**
 * 
 * 
 */
class DatabaseSocketPDO 
{	
	/**
	 *
	 * @var type 
	 */
	private $pdo = null; 

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
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		); 

		##
		$this->pdo = new PDO($dsn,$args['user'],$args['pass'],$opt);		
	
		##
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		##
		$this->prefix = $args['pref'];		
	}
	
	/**
	 * 
	 * @param type $sql
	 */
	public function query($sql) {
			 	
		##
		$this->pdo->query($sql);		
	}

	/**
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getRow($sql) {
		
		##
		$s = $this->pdo->prepare($sql);
		
		##
		$s->execute();

		##
		return $s->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getResults($sql) {
		
		##
		$statament = $this->pdo->prepare($sql);
		
		##
		$statament->execute();

		##
		$results = $statament->fetchAll(PDO::FETCH_ASSOC);
				
		##
		return $results;
	}
	
	/**
	 * 
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getColumn($sql) {
		
		##
		$statament = $this->pdo->prepare($sql);
		
		##
		$statament->execute();

		##
		$column = array(); 
		
		##
		while($row = $statament->fetch()){
			$column[] = $row[0];
		}
				
		##
		return $column;		
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
		return $this->pdo->lastInsertId();		
	}
	
	/**
	 * 
	 * 
	 */
	public function transact() {
		
		##
		$this->pdo->beginTransaction();		
	}
	
	/**
	 * 
	 */
	public function commit() {

		##
		$this->pdo->commit();				
	}
	
	/**
	 * 
	 */
	public function rollback() {

		##
		$this->pdo->rollBack();				
	}
}