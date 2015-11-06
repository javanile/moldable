<?php
/**
 *
 *
 *
 *
 *
 *
 * 
\*/
namespace Javanile\SchemaDB\Database;

/**
 * 
 */
use PDO;
use PDOException;

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
	public function connect($args)
    {
		//
		$dsn = "mysql:host={$args['host']};dbname={$args['name']}";
		
		//
		$opt = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		); 

		//
		$this->pdo = new PDO($dsn,$args['user'],$args['pass'],$opt);		
	
		//
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//
		$this->prefix = $args['pref'];		
	}
    
	/**
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getRow($sql, $params=null) {
		
        //
        $stmt = $this->execute($sql, $params);
		
		//
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getResults($sql, $params=null) {

		//
		$stmt = $this->execute($sql, $params);
		
		//
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    /**
	 *
	 * @param type $sql
	 * @return type
	 */
	public function getResultsAsObjects($sql, $params=null) {

		//
		$stmt = $this->execute($sql, $params);

		//
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * 
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getColumn($sql, $values=null) {
		
		//
		$stmt = $this->execute($sql, $values);

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
	public function getValue($sql, $values=null) {
		
		//
		$stmt = $this->execute($sql, $values);
		
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
		return $this->prefix;		
	}
	
	/**
	 * Return last insert id 
	 * 
	 * @return type
	 */
	public function lastInsertId() 
	{
		//
		return $this->pdo->lastInsertId();		
	}
	
	/**
	 * 
	 * 
	 */
	public function transact() {
		
		//
		$this->pdo->beginTransaction();		
	}
	
	/**
	 * 
	 */
	public function commit() {

		//
		$this->pdo->commit();				
	}
	
	/**
	 * 
	 */
	public function rollback() {

		//
		$this->pdo->rollBack();				
	}

    /**
     *
     */
    private function execute($sql, $values)
    {
        //
		$stmt = $this->pdo->prepare($sql);

        //
        if (is_array($values)) {
            foreach($values as $token => $value) {
                $stmt->bindValue($token, $value);
            }
        }

		//
		try {
			$stmt->execute();
		}

		//
		catch (PDOException  $Exception) {
			throw new DatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ) );
		}

        //
        return $stmt;
    }
}