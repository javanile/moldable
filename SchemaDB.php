<?php
/*\
 *
 * Copyright (c) 2012-2015 Bianco Francesco
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files "schemadb", to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
\*/

/*\
 * 
 * Thanks to SourceForge.net 
 * for your mission on the web
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * Main class prototyping a SchemaDB connection with MySQL database
 * 
 * <code>
 * <?php
 * ## Create SchemaDB connection
 * $conn = new SchemaDB(array(
 *		'host' => 'localhost',
 *		'user' => 'root',
 *		'pass' => 'root',
 *		'name' => 'db_schemadb',
 *		'pref' => 'tbl_',
 * ));
 * 
 * ## Create Table on database
 * $conn->update(array(
 *		'Table1' => array(
 *			'Field1' => 0,
 *			'Field2' => "",
 *		)
 * ));
 * ?> 
 * </code>
 */
class SchemaDB {

	/**
	 * Constant to enable debug print-out
	 */
	const DEBUG	= 1;
	
	/**
	 * Currenti release version number
	 */
	const VERSION = '0.3.0'; 			
	
	/**
	 * Constant to handle database interaction (execute)
	 */ 
	const QUERY = 0; 
	const CONNECT = 1; 
	const GET_ROW = 2;
	const GET_PREFIX = 3;
	const GET_LAST_ID = 4;
	const GET_RESULTS = 5;

	## 
	private static $default = null; 
	
	## 
	private $db = null;
			
	/**
	 * Construct and connect a SchemaDB drive 
	 * to mysql database best way to start use it
	 * 
	 * @param array $args Array with connection parameters
	 */
	public function __construct($args) {
		
		## 
		$this->connect($args);
		
		##
		static::setDefault($this);		
	}
	
	/**
	 * Apply schema on the db
	 * 
	 * @param type $schema
	 * @return type
	 */ 
	public function apply($schema) {	
		
		##
		return $this->update($schema);	
	}
	
	/**
	 * Update db via schema
	 * 
	 * @param type $schema
	 * @return type
	 */ 
	public function update($schema) {

		## retrive queries
		$q = $this->diff($schema);

		## execute queries
		if (count($q)>0) {
			
			##
			foreach($q as $s) {			
				
				##
				$this->query($s);
			}				
		}

		## return queries
		return $q;
	}

	/**
	 * Update database table via schema
	 * 
	 * @param string $table real table name to update 
	 * @param type $schema 
	 * @return type
	 */ 
	public function updateTable($table,$schema,$parse=true) {

		## retrive queries
		$q = $this->diffTable($table,$schema,$parse);
		
		## execute queries
		if ($q && count($q)>0) {
			
			## loop throu all queries calculated and execute it
			foreach($q as $s) {			
			
				## execute each queries
				$this->query($s);
			}				
		}

		## return queries
		return $q;
	}

	## generate query to align db
	public function diff($schema,$parse=true) {

		## prepare
		$s = $parse ? Parser::parseSchema($schema) : $schema;		
				
		## get prefix string 
		$p = $this->getPrefix();	
		
		## output container for rescued SQL query
		$o = array();

		## loop throu the schema
		foreach($s as $t => $d) {
			
			##
			$q = $this->diffTable($p.$t, $d, false);		
						
			##
			if (count($q)>0) {
				$o = array_merge($o, $q);
			}
		}

		## return estimated sql query
		return $o;	
	}

	/**
	 * generate query to align table
	 *
	 * @param type $table
	 * @param type $schema
	 * @param type $parse
	 * @return type
	 */
	public function diffTable($table,$schema,$parse=true) {	
		
		## parse input schema if required
		$s = $parse ? Parser::parseSchemaTable($schema) : $schema;
		
		## 
		$t = $parse ? $this->getPrefix() . $table : $table;
		
		## sql query to test table exists
		$q = "SHOW TABLES LIKE '{$t}'";
		
		## test if table exists
		$e = $this->getRow($q);
		
		## if table no exists return sql statament for creating this
		if (!$e) {
			return array(Mysql::createTable($t, $s));					
		}

		## used as output array 
		$o = array();
		
		## used as output array 
		$z = array();
		
		## describe table get current table description
		$a = $this->descTable($t);
		
		##
		$p = $this->diff_table_field_primary_key($a);
		
		## test field definition
		foreach($s as $f=>$d) {			
			
			##
			$this->diff_table_field($a,$f,$d,$t,$o,$z); 			
		}
		
		##
		if ($p && count($z) > 0) {
			$a[$p]['Key'] = '';
			$a[$p]['Extra'] = '';				
			$z[] = Mysql::alter_table_drop_primary_key($t);									
			$z[] = Mysql::alter_table_change($t,$p,$a[$p]);
		}		
			
		##
		return array_merge(array_reverse($z),$o);
	}

	##
	public function diff_table_field($a,$f,$d,$table,&$o,&$z) {
				
		## check if column exists in current db
		if (!isset($a[$f])) {
			
			##
			$q = Mysql::alter_table_add($table,$f,$d);
			
			## add primary key column
			if ($d['Key'] == 'PRI') {
				$z[] = $q;
			} 
			
			## add normal column
			else {
				$o[] = $q;
			}			
		} 
				
		## check if column need to be updated				
		else if ($this->diff_table_field_attributes($a,$f,$d)) {
							
			##
			$q = Mysql::alter_table_change($table,$f,$d);
			
			## alter column that lose primary key
			if ($a[$f]['Key'] == 'PRI' || $d['Key'] == 'PRI') {
				$z[] = $q;						
			} 
									
			## alter colum than not interact with primary key
			else {
				$o[] = $q;
			}
		}		
	}
	
	/**
	 * 
	 * 
	 * @param type $a
	 * @param type $f
	 * @param type $d
	 * @return boolean
	 */
	public function diff_table_field_attributes($a,$f,$d) {
					
		## loop throd current column property
		foreach($a[$f] as $k=>$v) {
			
			##
			#echo $k.': '.$d[$k].' !== '.$v.' = '.($d[$k] != $v).'<br/>';
			
			## if have a difference
			if ($d[$k] != $v) { return true; }	
		}

		##
		return false;
	}
	
	##
	public function diff_table_field_primary_key($a) {
		
		## loop throd current column property
		foreach ($a as $f=>$d) {

			## if have a difference
			if ($d['Key'] == 'PRI') { return $f; }	
		}

		##
		return false;		
	}
	
	##
	public function desc() {
		
		##
		$p = $this->getPrefix();		
		
		##
		$q = "SHOW TABLES LIKE '{$p}%'";
		
		##
		$l = $this->getResults($q);
				
		##
		if (!count($l)) { return; }
		
		##
		$r = array();
		
		##
		foreach($l as $t) {

			##
			$t = reset($t);

			##
			$r[$t] = $this->descTable($t);				
		}
		
		##
		return $r;			
	}
	
	## describe table
	public function descTable($table) {
		
		##
		$q = "DESC {$table}";
		
		##
		$i = $this->getResults($q);
		
		##
		$a = array();		
		
		##
		$n = 0;
		
		##
		$b = false;
		
		##
		foreach($i as $j) {		
			$j['Before'] = $b;		
			$j['First']	= $n === 0;
			$a[$j['Field']] = $j;					
			$b = $j['Field'];
			$n++;
		}
		
		##
		return $a;
	}

	/**
	 * Retrieve default SchemaDB connection
	 * 
	 * @return type 
	 */
	public static function getDefault() {
		
		## return static $default
		return static::$default;
	}
	
	/**
	 * 
	 * @param type $schemadb
	 */
	public static function setDefault($schemadb) {
		
		## if no default SchemaDB connection auto-set then-self
		if (static::$default === null) {			
			
			## set current SchemaDB connection to default
			static::$default = &$schemadb;
		}
	}
	
	/**
	 * Init database connection
	 * 
	 * @param type $host
	 * @param type $username
	 * @param type $password
	 * @param type $database
	 * @param type $prefix
	 * @return \SourceForge\SchemaDB\SchemaDB_ezSQL_mysql
	 */
	private function connect($args) {
		
		## check arguments for connection
		# TODO: controls $args field for validate id 
		
		## execute connection
		$this->execute(static::CONNECT, $args);
	}
	
	/**
	 * Execute SQL query to database
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function query($sql) {
		
		##
		return $this->execute(static::QUERY, $sql); 
	}
	
	/**
	 * Return current database prefix used
	 * 
	 * @return type
	 */
	public function getPrefix() {
		
		##
		return $this->execute(static::GET_PREFIX); 
	}
	
	/**
	 * 
	 * @return type
	 */
	public function getLastId() {
		
		##
		return $this->execute(static::GET_LAST_ID); 
	}
	
	/**
	 * 
	 * 
	 * @param type $sql
	 * @return type
	 */
	public function getRow($sql) {
		
		##
		return $this->execute(static::GET_ROW, $sql); 
	}
	
	/**
	 * Get a list/array of record from database
	 * based on SQL query passed
	 * 
	 * @param string $sql
	 * @return array
	 */
	public function getResults($sql) {
		
		## e
		return $this->execute(static::GET_RESULTS, $sql); 
	}
	
	/**
	 * 
	 * @param type $method
	 * @param array/string $args
	 * @return type
	 */ 
	public function execute($method, $args=null) {
				
		## debug the queries
		if (static::DEBUG) {
			$method_label = array('QUERY','CONNECT','GET_ROW','GET_PREFIX','GET_LAST_ID','GET_RESULTS');
			echo '<pre style="border:1px solid #9F6000;margin:0 0 1px 0;padding:2px;color:#9F6000;background:#FEEFB3;"><strong>'.str_pad($method_label[$method],12,' ',STR_PAD_LEFT).'</strong>'.($args?': '.json_encode($args):'').'</pre>';			
		}
	
		## select appropriate method
		switch ($method) {
			
			##
			case static::CONNECT:
				
				##
				$this->db = new SchemaDB_ezSQL_mysql(
					$args['user'],
					$args['pass'],
					$args['name'],
					$args['host']
				);
				
				##
				$this->db->prefix = $args['pref']; 	
				
				##
				return true;   	
				
			##	
			case static::QUERY:	return $this->db->query($args); 
			
			##
			case static::GET_ROW: return $this->db->get_row($args,ARRAY_A);
			
			##
			case static::GET_PREFIX: return $this->db->prefix;
			
			##
			case static::GET_LAST_ID: return $this->db->insert_id;
			
			##	
			case static::GET_RESULTS: return $this->db->get_results($args,ARRAY_A); 
			
			##
			default: die("execute method not exists");
		}				
	}

	/**
	 * printout database status and info
	 */ 
	public function dump() {
		
		## describe databse
		$s = $this->desc();
		
		##	
		echo '<pre><table border="1" style="text-align:center">';			
		
		##
		if ($s) {
						
			##
			foreach($s as $t => $d) {

				##
				echo '<tr><th colspan="9">'.$t.'</th></tr>';
				echo '<tr><td>&nbsp;</td>';			
				$r = key($d);			
				foreach($d[$r] as $k=>$v) {
					echo '<th>'.$k.'</th>';
				}
				echo '</tr>';
				foreach($d as $f => $a) {
					echo '<tr>';
					echo '<th>'.$f.'</th>';
					foreach($a as $k=>$v) {
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

/**
 * A collection of MySQL stataments builder
 * used with mysql query template and place-holder replacing
 */
class Mysql {
	
	##
	private static $default = array(
		'attributes' => array(
			'type' => 'int(10)',
		),
	);
	
	##
	public static function column_definition($d,$o=true) {
			
		##
		$t = isset($d['Type']) ? $d['Type'] : static::$default['attributes']['type'];
		$u = isset($d['Null']) && ($d['Null']=="NO" || !$d['Null']) ? 'NOT NULL' : 'NULL';
		$l = isset($d['Default']) && $d['Default'] ? "DEFAULT '$d[Default]'" : '';
		$e = isset($d['Extra']) ? $d['Extra'] : '';
		$p = isset($d['Key']) && $d['Key'] == 'PRI' ? 'PRIMARY KEY' : '';
		
		##
		$q = $t.' '.$u.' '.$l.' '.$e.' '.$p;
		
		##
		if ($o) {
			$f = isset($d["First"])&&$d["First"] ? 'FIRST' : '';
			$b = isset($d["Before"])&&$d["Before"] ? 'AFTER '.$d["Before"] : '';
			$q.= ' '.$f.' '.$b;
		} 
		
		##
		return $q;
	}
	
	/**
	 * Prepare sql code to create a table
	 * 
	 * @param string $t	The name of table to create 
	 * @param array	$s Skema of the table contain column definitions		
	 * @return string Sql code statament of CREATE TABLE
	 */
	public static function createTable($t,$s) {

		##
		$e = array();

		## loop throut schema
		foreach($s as $f=>$d) {
			
			##
			if (is_numeric($f) && is_string($d)) {
				
				##
				$f = $d;
				
				##
				$d = array();
			}	
			
			##
			$e[] = $f.' '.static::column_definition($d,false);
		}	

		## implode 
		$i = implode(',',$e);

		## template sql to create table
		$q = "CREATE TABLE {$t} ({$i})";

		## return the sql
		return $q;
	}

	##
	public static function alter_table_add($t,$f,$d) {
		
		##
		$c = Mysql::column_definition($d);
		
		##
		$q = "ALTER TABLE {$t} ADD {$f} {$c}";	
		
		##
		return $q; 	
	}
	
	## retrieve sql to alter table definition
	public static function alter_table_change($t,$f,$d) {
		
		##
		$c = Mysql::column_definition($d);
		
		##
		$q = "ALTER TABLE {$t} CHANGE {$f} {$f} {$c}";	
		
		##
		return $q; 
	}

	## retrive query to remove primary key
	public static function alter_table_drop_primary_key($t) {
	
		##
		$q = "ALTER TABLE {$t} DROP PRIMARY KEY";
		
		##
		return $q;
	}	
}

/**
 * 
 * 
 * 
 * 
 */
class Parser {
	
	##
	private static $default = array(
		'attribute' => array(
			'type'		=> 'int(10)',
			'null'		=> 'YES',
			'key'		=> '',
			'default'	=> '',
			'extra'		=> '',
		),
	);
		
	## parse a multi-table schema to sanitize end explode implicit info
	public static function parseSchema($schema) {	
		
		##
		$s = array();

		##
		foreach($schema as $t => $f) {
		
			##
			$s[$t] = static::parseSchemaTable($f);
		}

		##
		return $s;
	}
	
	## parse table schema to sanitize end explod implicit info
	public static function parseSchemaTable($schema) {	
			
		##
		$s = array();
		
		##
		$b = false;
		
		##
		foreach($schema as $f=>$d) {
		
			##
			$s[$f] = static::parseSchemaTableColumn($d,$f,$b);	
			
			##
			$b = $f;
		}

		##
		return $s;
	}
	
	/**
	 * Build mysql column attribute set
	 * 
	 * @param type $notation
	 * @param type $field
	 * @param type $before_field
	 * @return string
	 */ 
	public static function parseSchemaTableColumn($notation,$field=null,$before_field=null) {
		
		## default schema of a column
		$d = array(
			'Field'		=> $field,
			'Type'		=> static::$default['attribute']['type'],
			'Null'		=> static::$default['attribute']['null'],
			'Key'		=> static::$default['attribute']['key'],
			'Default'	=> static::$default['attribute']['default'],
			'Extra'		=> static::$default['attribute']['extra'],
			'Before'	=> $before_field,
			'First'		=> !$before_field,
		);
		
		##
		$t = static::get_type($notation);

		##
		switch ($t) {

			##
			case 'schema':
				foreach(static::get_schema($notation) as $a=>$v) {
					$d[$a] = $v;
				}
				break;
			
			case 'date':
				$d['Type'] = 'date';
				break;

			case 'datetime': 
				$d['Type'] = 'datetime';
				break;
				
			case 'primary_key':
				$d['Type'] = 'int(10)';
				$d['Null'] = 'NO';
				$d['Key'] = 'PRI';
				$d['Extra'] = 'auto_increment';
				break;	

			case 'string':						
				$d['Type'] = 'varchar(255)';
				break;

			case 'boolean': 
				$d['Type'] = 'tinyint(1)';
				$d['Default'] = (int)$notation;
				$d['Null'] = 'NO';	
				break;
				
			case 'int': 
				$d['Type'] = 'int(10)';
				$d['Default'] = (int)$notation;
				$d['Null'] = 'NO';			
				break;
				
			case 'float': 
				$d['Type'] = 'float(12,2)';
				$d['Default'] = (int)$notation;
				$d['Null'] = 'NO';			
				break;
				
			case 'array':
				$d['Default'] = $notation[0];
				$d['Null'] = in_array(null,$notation) ? 'YES' : 'NO';							
				$t = array();
				foreach($notation as $i) {
					if ($i!==null) {
						$t[] = "'{$i}'";
					}					
				}
				$d['Type'] = 'enum('.implode(',',$t).')';
				break;									
		}
		
		return $d;
	}
	
	##
	public static function get_type($notation) {

		##
		$t = gettype($notation);

		##
		switch ($t) {
			
			##
			case 'string':
				if (preg_match('/^<\{[A-Za-z_][0-9A-Za-z_]*\}>$/i',$notation)) {
					return 'class';										
				} else if (preg_match('/^<\{.*\}>$/i',$notation)) {
					return 'schema';					
				} else if (preg_match('/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]/',$notation)) {				
					return 'datetime';													
				} else if (preg_match('/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]/',$notation)) {
					return 'date';													
				} else {				
					return 'string';
				}
			
			##
			case 'NULL':	
				return 'string';

			##
			case 'boolean':
				return 'boolean';

			##
			case 'integer':
				return 'int';

			##	
			case 'double':
				return 'float';

			case 'array':
				if ($notation && $notation == array_values($notation)) {
					return 'array';
				} else {
					return 'schema';
				}			
		}	
		
	}

	##
	public static function getValue($notation) {

		##
		$t = static::get_type($notation);

		##
		switch($t) {
			
			##
			case 'int': 
				return (int) $notation;		
			
			##
			case 'boolean':
				return (boolean) $notation;		
			
			##
			case 'primary_key': 
				return NULL;
				
			##	
			case 'string':
				return (string) $notation;
			
			##	
			case 'float': 
				return (float) $notation;
				
			##	
			case 'class':
				return NULL;
			
			##
			case 'array': 
				return NULL;
			
			##
			case 'date': 
				return static::parse_date($notation);
			
			##	
			case 'datetime': 
				return static::parse_datetime($notation);
			
			##	
			case 'schema': 
				return null;
			
			##	
			case 'column': 
				return null;	

			##	
			default:	
				trigger_error("No PSEUDOTYPE value for '{$t}' => '{$notation}'",E_USER_ERROR);		
		}				
	}
	
	##
	public static function get_schema($notation) {
	
		##
		return json_decode(trim($notation,'<>'),true);
	}
	
	## handle creation of related object
	public static function object_build($d,$a,&$r) {
		
		##
		$t = schemadb::get_type($d);
		
		##
		switch($t) {
			case 'class':
				$c = schemadb::get_class($d);
				$o = new $c();
				$o->fill($a);
				$o->store();
				$k = $o::primary_key();
				$r = $o->{$k};
				break;
		}
	}
	
	## printout database status/info
	public static function parse_date($date) {
		
		##
		if ($date != '0000-00-00') {
			return @date('Y-m-d',@strtotime(''.$date));
		} else {
			return null;
		} 			
	}
	
	## printout database status/info
	public static function parse_datetime($datetime) {
		if ($datetime!='0000-00-00 00:00:00') {
			return @date('Y-m-d H:i:s',@strtotime(''.$datetime));
		} else {
			return null;
		} 			
	}
		
	##
	public static function escape($value) {
		return mysql_real_escape_string(stripslashes($value));
	}	
	
	##
	public static function encode($value) {

		##
		$t = gettype($value);

		##
		if ($t == 'double') {
			$v = number_format($value,2,'.','');
		}

		##
		return $v;		
	}
}

/**
 * static part of sdbClass
 * 
 *  
 */
class Table {
	
	## schemadb mysql constants for rapid fields creation
	const PRIMARY_KEY	= '<{"Key":"PRI","Extra":"auto_increment"}>';
	const VARCHAR		= '<{"Type:varchar(255)}>';
	const VARCHAR_80	= '<{"Type:varchar(80)}>';
	const VARCHAR_255	= '<{"Type:varchar(255)}>';
	const TEXT			= '<{"Type":"text"}>';
	const INT			= '<{"Type:int(10)}>';
	const INT_10		= '<{"Type:int(10)}>';
	const INT_14		= '<{"Type:int(14)}>';
	const FLOAT			= '<{"Type:float(14,4)}>';
	const FLOAT_14_4	= '<{"Type:float(14,4)}>';
	const TIME			= '00:00:00';
	const DATE			= '0000-00-00';
	const DATETIME		= '0000-00-00 00:00:00';
		
	## bundle to collect info and stored cache
	protected static $internal = array(
		
		##
		'cache'	=> null,
		
		##
		'exclude' => array(
			'SchemaDB',
			'class',
			'table',
			'internal',		
		),		
	);
				
	## retrieve table name
	public static function table() {
		
		## get prefix
		$p = static::getSchemaDB()->getPrefix();
		
		## check various possible table-name definition
		if (isset(static::$table)) {			
			$t = static::$table; 
		} else if (isset(static::$class)) {			
			$t = static::$class; 
		} else {			
			$t = get_called_class();
		}
		
		## return complete table name
		return $p.$t;
	}
	
	## retrieve static class name
	public static function getClass() {
			
		##
		return isset(static::$class) ? static::$class : get_called_class();
	}
	
	##
	public static function getSchemaDB() {
		
		##
		return isset(static::$SchemaDB) ? static::$SchemaDB : SchemaDB::getDefault();
	}
			
	## 
	public static function make($data=null) {
		
		##
		$o = new static();
		
		##
		if ($data) {
			$o->fill($data);
		}
		
		##
		return $o;
	}
	
	## 
	public static function build($data=null) {
			
		##
		return static::make($data);
	}
		
	##
	public static function all() {
		
		##
		static::schemadb_update();
		
		##
		$t = static::table();		
		
		##
		$q = "SELECT * FROM {$t}";
		
		##
		$r = static::getSchemaDB()->getResults($q);
		
		##
		$a = array();
		
		##
		foreach($r as $i=>$o) {
			$a[$i] = static::make($o);
		}
		
		##
		return $a;
	}
	
	##
	public static function query($query) {
				
		##
		$x = $query;
		
		##
		$t = self::table();		
		
		## where block for the query
		$h = array();
		
		##
		if (isset($x['where'])) {
			$h[] = "(".$x['where'].")";
		}
		
		##
		foreach($x as $k=>$v) {
			
			##
			if (in_array($k,array('order','where','limit'))) {
				continue;
			}
			
			##
			$h[] = "{$k} = '{$v}'";			
		}
		
		##
		$w = count($h) > 0 ? 'WHERE '.implode(' AND ',$h) : '';
		
		## order by block
		$o = isset($x['order']) ? 'ORDER BY '.$x['order'] : '';		
		
		## order by block
		$l = isset($x['limit']) ? 'LIMIT '.$x['limit'] : '';		
		
		## build query
		$q = "SELECT * FROM {$t} {$w} {$o} {$l}";				
		
		## fetch res
		$r = static::getSchemaDB()->getResults($q);
				
		##
		foreach($r as &$i) {
			$i = static::make($i);
		}
		
		##
		return $r;
	}
	
	##
	public static function first() {

		##
		$t = static::table();		
	
		##
		$s = "SELECT * FROM {$t} LIMIT 1";
		
		##
		$r = schemadb::execute('row',$s);		
		
		##
		if ($r) {
			return static::build($r);
		}		
		
	}
	
	## alias 6char of ping
	public static function exists($query) {
		return static::ping($query);
	}
	
	##
	public static function ping($query) {
		
		##
		static::schemadb_update();
		
		##
		$t = self::table();		
		$w = array();
		
		##
		if (isset($query['where'])) {
			$w[] = $query['where'];
			unset($query['where']);
		}
		
		##
		foreach(static::skema() as $f=>$d) {
			if (isset($query[$f])) {
				$v = $query[$f];
				$w[] = "{$f}='$v'";		
			}
		}
		
		##
		$w = count($w)>0 ? 'WHERE '.implode(' AND ',$w) : '';
		
		##
		$s = "SELECT * FROM {$t} {$w} LIMIT 1";
		
		##
		$r = schemadb::execute('row',$s);		
		
		##
		if ($r) {
			return self::build($r);
		}		
	}
	
	##
	public static function submit($query) {
		
		##
		$o = static::ping($query);
		
		##
		if (!$o) {
			$o = static::build($query);
			$o->store();
		}
		
		##
		return $o;
	}
	
	##
	public static function insert($query) {
		
		##
		$o = static::build($query);
		$o->store_insert();
		
		##
		return $o;
	}
	
	/**
	 * 
	 * @param type $query
	 * @param type $values
	 * @return type
	 */
	public static function update($query, $values) {
				
		##
		$o = static::build($query);
		$o->store_update();
		
		##
		return $o;
	}	
		
	/**
	 * Import records from a source
	 * 
	 * @param type $source
	 */
	public static function import($source) {
		
		## source is array loop throut records
		foreach($source as $record) {
			
			## insert single record
			static::insert($record);			
		}
	}
	
	/**
	 * Encode/manipulate field on object 
	 * based on encode_ static method of class 
	 * 
	 * @param type $object
	 * @return type
	 */
	public static function encode($object) {
		
		##
		$c = static::getClass();
		
		##
		foreach($object as $f=>$v) {
			
			##
			$m = 'encode_'.$f;
			
			##
			if (!method_exists($c,$m)) { continue; }
				
			##
			else if (is_object($object)) {
				$data->{$f} = call_user_func($c.'::'.$m,$v);
			} 

			##
			else if (is_array($object)) {
				$data[$f] = call_user_func($c.'::'.$m,$v);					
			}
		}
		
		##
		return $object;
	}
	
	##
	public static function decode($data) {

		##
		$c = get_called_class();
		
		##
		foreach($data as $f=>$v) {
			$m = 'decode_'.$f;
			if (method_exists($c,$m)) {
				if (is_object($data)) {
					$data->{$f} = call_user_func($c.'::'.$m,$v);
				} else {
					$data[$f] = call_user_func($c.'::'.$m,$v);					
				}
			}  				
		}
		
		##
		return $data;
	}
	
	##
	public static function map($data,$map) {
		
		##
		$o = static::make($data);
		
		##
		foreach($map as $m=>$f) {
			$o->{$f} = isset($data[$m]) ? $data[$m] : '';			
		}
		
		##
		return $o;
	}
	
	##
	public static function dump($list=null) {

		##
		$a = $list ? $list : static::all();
		
		##
		$t = static::table();

		##
		$r = key($a);
			
		##
		$n = count($a) > 0 ? count((array) $a[$r]) : 1;
		
		##
		echo '<pre><table border="1" style="text-align:center"><thead><tr><th colspan="'.$n.'">'.$t.'</th></tr>';
				
		##
		echo '<tr>';
		foreach($a[$r] as $f=>$v) {
			echo '<th>'.$f.'</th>';
		}				
		echo '</tr></thead><tbody>';
				
		##
		foreach($a as $i=>$r) {			
			echo '<tr>';
			foreach($r as $f=>$v) {
				echo '<td>'.$v.'</td>';
			}				
			echo '</tr>';
		}
		
		##
		echo '</tbody></table></pre>';
	}
	
	##
	public static function desc() {
			
		##
		$t = static::table();

		##
		$s = static::getSchemaDB()->desc_table($t);
		
		##
		echo '<table border="1" style="text-align:center"><tr><th colspan="8">'.$t.'</td></th>';
			
		##
		$d = reset($s);
		
		##
		echo '<tr>';				
		foreach($d as $a=>$v) { 
			echo '<th>'.$a.'</th>';					
		}				
		echo '</tr>';						
			
		##
		foreach($s as $d) {				
			echo '<tr>';				
			foreach($d as $a=>$v) { 
				echo '<td>'.$v.'</td>'; 				
			}
			echo '</tr>';											
		}
			
		##
		echo '</table>';			
	}
	
	## instrospect and retrieve element schema
	public static function skema() {		
		
		##
		$c = static::getClass();		
		
		##
		$f = get_class_vars($c);
		
		##
		$s = array();
		
		##
		foreach($f as $k=>$v) {
			if (!in_array($k,static::$internal['exclude'])) {
				$s[$k] = $v;
			}
		}
		
		##
		return $s;
	}
		
	## update db table based on class schema
	public static function schemadb_update() {		
		
		## get class name
		$c = static::getClass();		
		
		## avoid re-update by check the cache
		if (isset(static::$internal['cache'][$c]['updated'])) {	return; }	
		
		## get table name
		$t = static::table();		
		
		## and model schema
		$s = Parser::parseSchemaTable(static::skema());

		## have a valid schema update db table
		if (count($s) > 0) {
			static::getSchemaDB()->updateTable($t, $s, false);
		}	

		## cache last update avoid multiple call
		static::$internal['cache'][$c]['updated'] = time();

		## debug output
		#if (SchemaDB::DEBUG) {
		#	echo '<pre style="border:1px solid #9F6000;margin:0 0 1px 0;padding:2px;color:#9F6000;background:#FEEFB3;">';
		#	echo '<strong>'.str_pad('update',10,' ',STR_PAD_LEFT).'</strong>: '.$c.'</pre>';						
		#}
	}
	
	##
	public static function connect($conn=null) {
		
		##
		if (schemadb::connected()) {
		
			##
			static::schemadb_update();		
		}
	}
	
	## usefull mysql func
	public static function now() { 
		
		##
		return @date('Y-m-d H:i:s'); 
	}
}

/**
 * 
 * 
 * 
 * 
 */
class Model extends Table {
	
	/**
	 * Load item from DB by primary key
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function load($id) {
		
		##
		$i = (int) $id;
		
		##
		$t = static::table();
		
		##
		$k = static::primary_key();		
		
		##
		$q = "SELECT * FROM {$t} WHERE {$k}='{$i}' LIMIT 1";		
		
		##
		$r = static::getSchemaDB()->getRow($q);
		
		##
		$o = static::make($r);
		
		##
		return $o;
	}
	
	## delete element by primary key or query
	public static function delete($query) {

		##
		$t = static::table();

		##
		if (is_array($query)) {
			
			## where block for the query
			$h = array();

			##
			if (isset($query['where'])) {
				$h[] = $query['where'];
			}

			##
			foreach($query as $k=>$v) {
				if ($k!='sort'&&$k!='where') {
					$h[] = "{$k}='{$v}'";
				}
			}
			
			##
			$w = count($h)>0 ? 'WHERE '.implode(' AND ',$h) : '';

			##
			$s = "DELETE FROM {$t} {$w}";
			
			## execute query
			static::getSchemaDB()->query($s);						
		} 
		
		##
		else if ($query > 0) {
			
			## prepare sql query
			$k = static::primary_key();
			
			##
			$i = (int) $query;
			
			## 
			$q = "DELETE FROM {$t} WHERE {$k}='{$i}' LIMIT 1";
			
			## execute query
			static::getSchemaDB()->query($q);	
		}		
	}		
	
	## drop table
	public static function drop($confirm=null) {
		
		##
		if ($confirm !== 'confirm') {
			return;
		}
		
		## prepare sql query
		$t = static::table();
		
		##
		$q = "DROP TABLE IF EXISTS {$t}";
		
		##
		$c = static::getClass();

		## clear cached
		unset(static::$internal['cache'][$c]['updated']);
		
		## execute query
		static::getSchemaDB()->query($q);
	}			
}

/**
 * self methods of sdbClass
 * 
 * 
 */
class Record extends Model {
	
	## constructor
	public function __construct() {
		
		## update database schema
		static::schemadb_update();	

		## prepare field values strip schema definitions
		foreach($this->fields() as $f) {
			
			##
			$this->{$f} = Parser::getValue($this->{$f});
		}
	}
		
	/**
	 * Assign value and store object
	 * 
	 * @param type $query
	 */
	public function assign($query) {
		
		##
		foreach($query as $k => $v) {
		
			##
			$this->{$k} = $v;			
		}
		
		##
		$this->store();
	}
	
	/**
	 * Auto-store element method
	 * 
	 * @return type
	 */
	public function store() {				
		
		## retrieve primary key
		$k = static::primary_key();		
		
		## based on primary key store action
		if ($k && $this->{$k}>0) {
			
			##
			return $this->store_update();						
		} else {
			
			##
			return $this->store_insert();			
		}
	}
		
	/**
	 * Fill field with set parser value from array
	 * 
	 * @param type $array
	 */
	public function fetch($array) {			
		
		##
		foreach($this->fields() as $f) {
			$this->set($f,$array[$f]);			
		}
		
		##
		$k = $this->primary_key();
		
		##
		if ($k) {
			$this->{$k} = isset($array[$k]) ? (int) $array[$k] : (int)$this->{$k}; 		
		}				
	}
	
	##
	public function fill($array) {		
		foreach($this->fields() as $f) {
			if (isset($array[$f])) {
				$this->{$f} = $array[$f];		
			}
		}
		
		$k = $this->primary_key();
		
		if ($k) {
			$this->{$k} = isset($array[$k]) ? (int) $array[$k] : (int)$this->{$k}; 		
		}	
	}
		
	/**
	 * Return fields names
	 * 
	 * @return type
	 */
	public function fields() {		
	
		##
		$c = get_class($this);
		$f = get_class_vars($c);
		$a = array();
		
		##
		foreach($f as $k=>$v) {
			if (!in_array($k, static::$internal['exclude'])) {
				$a[] = $k;
			}
		}
		
		##
		return $a;		
	}
	
	##
	public static function primary_key() {
		
		##
		$s = static::skema();		
		
		##
		foreach($s as $k=>$v) {			
			
			##
			if ($v === static::PRIMARY_KEY) {
				
				##
				return $k;
			}
		}
		
		##
		return false;
	}
} 

/**
 * canonical name
 * 
 * 
 */
class Storable extends Record {
	
	##
	public function store_update() {		
		
		## update database schema
		static::schemadb_update();	
		
		##
		$k = static::primary_key();
		
		##
		$e = array();
		
		##
		foreach($this->fields() as $f) {
			
			##
			if ($f == $k) { continue; }
			
			##
			$v = Parser::encode($this->{$f});
						
			##
			$e[] = "{$f} = '{$v}'";
		}
		
		##
		$s = implode(',',$e);

		##
		$t = static::table();
		
		##
		$i = $this->{$k};
		
		##
		$q = "UPDATE {$t} SET {$s} WHERE {$k}='{$i}'";
		
		##
		static::getSchemaDB()->query($q);	
		
		##
		if ($k) {
			return $this->{$k};
		} 
		
		##
		else {
			return true;
		}
	}
	
	##
	public function store_insert($force=false) {		
			
		##
		static::schemadb_update();	
		
		##
		$c = array();
		$v = array();
		$k = static::primary_key();
		
		##
		foreach(static::skema() as $f=>$d) {
			
			##
			if ($f==$k&&!$force) {continue;}
			
			##
			$a = $this->{$f};
			$t = gettype($a);

			##
			switch($t) {

				##
				case 'double':
					$a = number_format($a,2,'.','');
					break;

				##
				case 'array':
					schemadb::object_build($d,$a,$r);
					$a = $r;
					break;

			}
				
			##
			$a = Parser::escape($a);
			
			##
			$c[] = $f;			
			$v[] = "'".$a."'";
		}
		
		##
		$c = implode(',',$c);
		$v = implode(',',$v);
		
		##
		$t = static::table();
		$q = "INSERT INTO {$t} ({$c}) VALUES ({$v})";
		
		##
		static::getSchemaDB()->query($q);
		
		##
		if ($k) {
			$i = static::getSchemaDB()->getLastId();	
			$this->{$k} = $i;
			return $i;
		} 
		
		##
		else {
			return true;
		}
	}
	
}

/**
 * -------------------------
 * ezSQL from Justin Vincent 
 * is a mysql best driver 
 * -------------------------
 */

## ezsql library embedded
/**********************************************************************
*  Author: Justin Vincent (jv@vip.ie)
*  Web...: http://justinvincent.com
*  Name..: ezSQL
*  Desc..: ezSQL Core module - database abstraction library to make
*          it very easy to deal with databases. ezSQLcore can not be used by
*          itself (it is designed for use by database specific modules).
*
*/

/**********************************************************************
*  ezSQL Constants
*/

define('EZSQL_VERSION','2.17');
define('OBJECT','OBJECT',true);
define('ARRAY_A','ARRAY_A',true);
define('ARRAY_N','ARRAY_N',true);

/**********************************************************************
*  Core class containg common functions to manipulate query result
*  sets once returned
*/

class schemadb_ezSQLcore
{

	var $trace            = false;  // same as $debug_all
	var $debug_all        = false;  // same as $trace
	var $debug_called     = false;
	var $vardump_called   = false;
	var $show_errors      = true;
	var $num_queries      = 0;
	var $last_query       = null;
	var $last_error       = null;
	var $col_info         = null;
	var $captured_errors  = array();
	var $cache_dir        = false;
	var $cache_queries    = false;
	var $cache_inserts    = false;
	var $use_disk_cache   = false;
	var $cache_timeout    = 24; // hours
	var $timers           = array();
	var $total_query_time = 0;
	var $db_connect_time  = 0;
	var $trace_log        = array();
	var $use_trace_log    = false;
	var $sql_log_file     = false;
	var $do_profile       = false;
	var $profile_times    = array();

	// added to integrate schemadb
	var $prefix	= "";
	
	// == TJH == default now needed for echo of debug function
	var $debug_echo_is_on = true;

	/**********************************************************************
	*  Constructor
	*/

	function ezSQLcore()
	{
	}

	/**********************************************************************
	*  Get host and port from an "host:port" notation.
	*  Returns array of host and port. If port is omitted, returns $default
	*/

	function get_host_port( $host, $default = false )
	{
		$port = $default;
		if ( false !== strpos( $host, ':' ) ) {
			list( $host, $port ) = explode( ':', $host );
			$port = (int) $port;
		}
		return array( $host, $port );
	}

	/**********************************************************************
	*  Print SQL/DB error - over-ridden by specific DB class
	*/

	function register_error($err_str)
	{
		// Keep track of last error
		$this->last_error = $err_str;

		// Capture all errors to an error array no matter what happens
		$this->captured_errors[] = array
		(
			'error_str' => $err_str,
			'query'     => $this->last_query
		);
	}

	/**********************************************************************
	*  Turn error handling on or off..
	*/

	function show_errors()
	{
		$this->show_errors = true;
	}

	function hide_errors()
	{
		$this->show_errors = false;
	}

	/**********************************************************************
	*  Kill cached query results
	*/

	function flush()
	{
		// Get rid of these
		$this->last_result = null;
		$this->col_info = null;
		$this->last_query = null;
		$this->from_disk_cache = false;
	}

	/**********************************************************************
	*  Get one variable from the DB - see docs for more detail
	*/

	function get_var($query=null,$x=0,$y=0)
	{

		// Log how the function was called
		$this->func_call = "\$db->get_var(\"$query\",$x,$y)";

		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}

		// Extract var out of cached results based x,y vals
		if ( $this->last_result[$y] )
		{
			$values = array_values(get_object_vars($this->last_result[$y]));
		}

		// If there is a value return it else return null
		return (isset($values[$x]) && $values[$x]!=='')?$values[$x]:null;
	}

	/**********************************************************************
	*  Get one row from the DB - see docs for more detail
	*/

	function get_row($query=null,$output=OBJECT,$y=0)
	{

		// Log how the function was called
		$this->func_call = "\$db->get_row(\"$query\",$output,$y)";

		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}

		// If the output is an object then return object using the row offset..
		if ( $output == OBJECT )
		{
			return $this->last_result[$y]?$this->last_result[$y]:null;
		}
		// If the output is an associative array then return row as such..
		elseif ( $output == ARRAY_A )
		{
			return $this->last_result[$y]?get_object_vars($this->last_result[$y]):null;
		}
		// If the output is an numerical array then return row as such..
		elseif ( $output == ARRAY_N )
		{
			return $this->last_result[$y]?array_values(get_object_vars($this->last_result[$y])):null;
		}
		// If invalid output type was specified..
		else
		{
			$this->show_errors ? trigger_error(" \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N",E_USER_WARNING) : null;
		}

	}

	/**********************************************************************
	*  Function to get 1 column from the cached result set based in X index
	*  see docs for usage and info
	*/

	function get_col($query=null,$x=0)
	{

		$new_array = array();

		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}

		// Extract the column values
		for ( $i=0; $i < count($this->last_result); $i++ )
		{
			$new_array[$i] = $this->get_var(null,$x,$i);
		}

		return $new_array;
	}


	/**********************************************************************
	*  Return the the query as a result set - see docs for more details
	*/

	function get_results($query=null, $output = OBJECT)
	{

		// Log how the function was called
		$this->func_call = "\$db->get_results(\"$query\", $output)";

		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}

		// Send back array of objects. Each row is an object
		if ( $output == OBJECT )
		{
			return $this->last_result;
		}
		elseif ( $output == ARRAY_A || $output == ARRAY_N )
		{
			if ( $this->last_result )
			{
				$i=0;
				foreach( $this->last_result as $row )
				{

					$new_array[$i] = get_object_vars($row);

					if ( $output == ARRAY_N )
					{
						$new_array[$i] = array_values($new_array[$i]);
					}

					$i++;
				}

				return $new_array;
			}
			else
			{
				return array();
			}
		}
	}


	/**********************************************************************
	*  Function to get column meta data info pertaining to the last query
	* see docs for more info and usage
	*/

	function get_col_info($info_type="name",$col_offset=-1)
	{

		if ( $this->col_info )
		{
			if ( $col_offset == -1 )
			{
				$i=0;
				foreach($this->col_info as $col )
				{
					$new_array[$i] = $col->{$info_type};
					$i++;
				}
				return $new_array;
			}
			else
			{
				return $this->col_info[$col_offset]->{$info_type};
			}

		}

	}

	/**********************************************************************
	*  store_cache
	*/

	function store_cache($query,$is_insert)
	{

		// The would be cache file for this query
		$cache_file = $this->cache_dir.'/'.md5($query);

		// disk caching of queries
		if ( $this->use_disk_cache && ( $this->cache_queries && ! $is_insert ) || ( $this->cache_inserts && $is_insert ))
		{
			if ( ! is_dir($this->cache_dir) )
			{
				$this->register_error("Could not open cache dir: $this->cache_dir");
				$this->show_errors ? trigger_error("Could not open cache dir: $this->cache_dir",E_USER_WARNING) : null;
			}
			else
			{
				// Cache all result values
				$result_cache = array
				(
					'col_info' => $this->col_info,
					'last_result' => $this->last_result,
					'num_rows' => $this->num_rows,
					'return_value' => $this->num_rows,
				);
				file_put_contents($cache_file, serialize($result_cache));
				if( file_exists($cache_file . ".updating") )
					unlink($cache_file . ".updating");
			}
		}

	}

	/**********************************************************************
	*  get_cache
	*/

	function get_cache($query)
	{

		// The would be cache file for this query
		$cache_file = $this->cache_dir.'/'.md5($query);

		// Try to get previously cached version
		if ( $this->use_disk_cache && file_exists($cache_file) )
		{
			// Only use this cache file if less than 'cache_timeout' (hours)
			if ( (time() - filemtime($cache_file)) > ($this->cache_timeout*3600) &&
				!(file_exists($cache_file . ".updating") && (time() - filemtime($cache_file . ".updating") < 60)) )
			{
				touch($cache_file . ".updating"); // Show that we in the process of updating the cache
			}
			else
			{
				$result_cache = unserialize(file_get_contents($cache_file));

				$this->col_info = $result_cache['col_info'];
				$this->last_result = $result_cache['last_result'];
				$this->num_rows = $result_cache['num_rows'];

				$this->from_disk_cache = true;

				// If debug ALL queries
				$this->trace || $this->debug_all ? $this->debug() : null ;

				return $result_cache['return_value'];
			}
		}

	}

	/**********************************************************************
	*  Dumps the contents of any input variable to screen in a nicely
	*  formatted and easy to understand way - any type: Object, Var or Array
	*/

	function vardump($mixed='')
	{

		// Start outup buffering
		ob_start();

		echo "<p><table><tr><td bgcolor=ffffff><blockquote><font color=000090>";
		echo "<pre><font face=arial>";

		if ( ! $this->vardump_called )
		{
			echo "<font color=800080><b>ezSQL</b> (v".EZSQL_VERSION.") <b>Variable Dump..</b></font>\n\n";
		}

		$var_type = gettype ($mixed);
		print_r(($mixed?$mixed:"<font color=red>No Value / False</font>"));
		echo "\n\n<b>Type:</b> " . ucfirst($var_type) . "\n";
		echo "<b>Last Query</b> [$this->num_queries]<b>:</b> ".($this->last_query?$this->last_query:"NULL")."\n";
		echo "<b>Last Function Call:</b> " . ($this->func_call?$this->func_call:"None")."\n";
		echo "<b>Last Rows Returned:</b> ".count($this->last_result)."\n";
		echo "</font></pre></font></blockquote></td></tr></table>".$this->donation();
		echo "\n<hr size=1 noshade color=dddddd>";

		// Stop output buffering and capture debug HTML
		$html = ob_get_contents();
		ob_end_clean();

		// Only echo output if it is turned on
		if ( $this->debug_echo_is_on )
		{
			echo $html;
		}

		$this->vardump_called = true;

		return $html;

	}

	/**********************************************************************
	*  Alias for the above function
	*/

	function dumpvar($mixed)
	{
		$this->vardump($mixed);
	}

	/**********************************************************************
	*  Displays the last query string that was sent to the database & a
	* table listing results (if there were any).
	* (abstracted into a seperate file to save server overhead).
	*/

	function debug($print_to_screen=true)
	{

		// Start outup buffering
		ob_start();

		echo "<blockquote>";

		// Only show ezSQL credits once..
		if ( ! $this->debug_called )
		{
			echo "<font color=800080 face=arial size=2><b>ezSQL</b> (v".EZSQL_VERSION.") <b>Debug..</b></font><p>\n";
		}

		if ( $this->last_error )
		{
			echo "<font face=arial size=2 color=000099><b>Last Error --</b> [<font color=000000><b>$this->last_error</b></font>]<p>";
		}

		if ( $this->from_disk_cache )
		{
			echo "<font face=arial size=2 color=000099><b>Results retrieved from disk cache</b></font><p>";
		}

		echo "<font face=arial size=2 color=000099><b>Query</b> [$this->num_queries] <b>--</b> ";
		echo "[<font color=000000><b>$this->last_query</b></font>]</font><p>";

			echo "<font face=arial size=2 color=000099><b>Query Result..</b></font>";
			echo "<blockquote>";

		if ( $this->col_info )
		{

			// =====================================================
			// Results top rows

			echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
			echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";


			for ( $i=0; $i < count($this->col_info); $i++ )
			{
				/* when selecting count(*) the maxlengh is not set, size is set instead. */
				echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$this->col_info[$i]->type}";
				if (!isset($this->col_info[$i]->max_length))
				{
					echo "{$this->col_info[$i]->size}";
				} else {
					echo "{$this->col_info[$i]->max_length}";
				}
				echo "</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$this->col_info[$i]->name}</span></td>";
			}

			echo "</tr>";

			// ======================================================
			// print main results

		if ( $this->last_result )
		{

			$i=0;
			foreach ( $this->get_results(null,ARRAY_N) as $one_row )
			{
				$i++;
				echo "<tr bgcolor=ffffff><td bgcolor=eeeeee nowrap align=middle><font size=2 color=555599 face=arial>$i</font></td>";

				foreach ( $one_row as $item )
				{
					echo "<td nowrap><font face=arial size=2>$item</font></td>";
				}

				echo "</tr>";
			}

		} // if last result
		else
		{
			echo "<tr bgcolor=ffffff><td colspan=".(count($this->col_info)+1)."><font face=arial size=2>No Results</font></td></tr>";
		}

		echo "</table>";

		} // if col_info
		else
		{
			echo "<font face=arial size=2>No Results</font>";
		}

		echo "</blockquote></blockquote>".$this->donation()."<hr noshade color=dddddd size=1>";

		// Stop output buffering and capture debug HTML
		$html = ob_get_contents();
		ob_end_clean();

		// Only echo output if it is turned on
		if ( $this->debug_echo_is_on && $print_to_screen)
		{
			echo $html;
		}

		$this->debug_called = true;

		return $html;

	}

	/**********************************************************************
	*  Naughty little function to ask for some remuniration!
	*/

	function donation()
	{
		return "<font size=1 face=arial color=000000>If ezSQL has helped <a href=\"https://www.paypal.com/xclick/business=justin%40justinvincent.com&item_name=ezSQL&no_note=1&tax=0\" style=\"color: 0000CC;\">make a donation!?</a> &nbsp;&nbsp;<!--[ go on! you know you want to! ]--></font>";
	}

	/**********************************************************************
	*  Timer related functions
	*/

	function timer_get_cur()
	{
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

	function timer_start($timer_name)
	{
		$this->timers[$timer_name] = $this->timer_get_cur();
	}

	function timer_elapsed($timer_name)
	{
		return round($this->timer_get_cur() - $this->timers[$timer_name],2);
	}

	function timer_update_global($timer_name)
	{
		if ( $this->do_profile )
		{
			$this->profile_times[] = array
			(
				'query' => $this->last_query,
				'time' => $this->timer_elapsed($timer_name)
			);
		}

		$this->total_query_time += $this->timer_elapsed($timer_name);
	}

	/**********************************************************************
	* Creates a SET nvp sql string from an associative array (and escapes all values)
	*
	*  Usage:
	*
	*     $db_data = array('login'=>'jv','email'=>'jv@vip.ie', 'user_id' => 1, 'created' => 'NOW()');
	*
	*     $db->query("INSERT INTO users SET ".$db->get_set($db_data));
	*
	*     ...OR...
	*
	*     $db->query("UPDATE users SET ".$db->get_set($db_data)." WHERE user_id = 1");
	*
	* Output:
	*
	*     login = 'jv', email = 'jv@vip.ie', user_id = 1, created = NOW()
	*/

	function get_set($params)
	{
		if( !is_array( $params ) )
		{
			$this->register_error( 'get_set() parameter invalid. Expected array in '.__FILE__.' on line '.__LINE__);
			return;
		}
		$sql = array();
		foreach ( $params as $field => $val )
		{
			if ( $val === 'true' || $val === true )
				$val = 1;
			if ( $val === 'false' || $val === false )
				$val = 0;

			switch( $val ){
				case 'NOW()' :
				case 'NULL' :
				  $sql[] = "$field = $val";
					break;
				default :
					$sql[] = "$field = '".$this->escape( $val )."'";
			}
		}

		return implode( ', ' , $sql );
	}

}


/**********************************************************************
*  Author: Justin Vincent (jv@jvmultimedia.com)
*  Web...: http://twitter.com/justinvincent
*  Name..: ezSQL_mysql
*  Desc..: mySQL component (part of ezSQL databse abstraction library)
*
*/

/**********************************************************************
*  ezSQL error strings - mySQL
*/

global $ezsql_mysql_str;

$ezsql_mysql_str = array
(
	1 => 'Require $dbuser and $dbpassword to connect to a database server',
	2 => 'Error establishing mySQL database connection. Correct user/password? Correct hostname? Database server running?',
	3 => 'Require $dbname to select a database',
	4 => 'mySQL database connection is not active',
	5 => 'Unexpected error while trying to select database'
);

/**********************************************************************
*  ezSQL Database specific class - mySQL
*/


class SchemaDB_ezSQL_mysql extends schemadb_ezSQLcore
{

	var $dbuser = false;
	var $dbpassword = false;
	var $dbname = false;
	var $dbhost = false;
	var $encoding = false;
	var $rows_affected = false;

	/**********************************************************************
	*  Constructor - allow the user to perform a qucik connect at the
	*  same time as initialising the ezSQL_mysql class
	*/

	public function __construct($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $encoding='')
	{
		$this->dbuser = $dbuser;
		$this->dbpassword = $dbpassword;
		$this->dbname = $dbname;
		$this->dbhost = $dbhost;
		$this->encoding = $encoding;
	}

	/**********************************************************************
	*  Short hand way to connect to mySQL database server
	*  and select a mySQL database at the same time
	*/

	function quick_connect($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $encoding='')
	{
		$return_val = false;
		if ( ! $this->connect($dbuser, $dbpassword, $dbhost,true) ) ;
		else if ( ! $this->select($dbname,$encoding) ) ;
		else $return_val = true;
		return $return_val;
	}

	/**********************************************************************
	*  Try to connect to mySQL database server
	*/

	function connect($dbuser='', $dbpassword='', $dbhost='localhost')
	{
		
		
		global $ezsql_mysql_str; $return_val = false;

		// Keep track of how long the DB takes to connect
		$this->timer_start('db_connect_time');

		// Must have a user and a password
		if ( ! $dbuser )
		{
			$this->register_error($ezsql_mysql_str[1].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($ezsql_mysql_str[1],E_USER_WARNING) : null;
		}
		// Try to establish the server database handle
		else if ( ! $this->dbh = @mysql_connect($dbhost,$dbuser,$dbpassword,true,131074) )
		{
			$this->register_error($ezsql_mysql_str[2].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($ezsql_mysql_str[2],E_USER_WARNING) : null;
		}
		else
		{
			$this->dbuser = $dbuser;
			$this->dbpassword = $dbpassword;
			$this->dbhost = $dbhost;
			$return_val = true;
		}

		return $return_val;
	}

	/**********************************************************************
	*  Try to select a mySQL database
	*/

	function select($dbname='', $encoding='')
	{
		global $ezsql_mysql_str; $return_val = false;

		// Must have a database name
		if ( ! $dbname )
		{
			$this->register_error($ezsql_mysql_str[3].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($ezsql_mysql_str[3],E_USER_WARNING) : null;
		}

		// Must have an active database connection
		else if ( ! $this->dbh )
		{
			$this->register_error($ezsql_mysql_str[4].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($ezsql_mysql_str[4],E_USER_WARNING) : null;
		}

		// Try to connect to the database
		else if ( !@mysql_select_db($dbname,$this->dbh) )
		{
			// Try to get error supplied by mysql if not use our own
			if ( !$str = @mysql_error($this->dbh))
				  $str = $ezsql_mysql_str[5];

			$this->register_error($str.' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($str,E_USER_WARNING) : null;
		}
		else
		{
			$this->dbname = $dbname;
			if ( $encoding == '') $encoding = $this->encoding;
			if($encoding!='')
			{
				$encoding = strtolower(str_replace("-","",$encoding));
				$charsets = array();
				$result = mysql_query("SHOW CHARACTER SET");
				while($row = mysql_fetch_array($result,MYSQL_ASSOC))
				{
					$charsets[] = $row["Charset"];
				}
				if(in_array($encoding,$charsets)){
					mysql_query("SET NAMES '".$encoding."'");						
				}
			}

			$return_val = true;
		}

		return $return_val;
	}

	/**********************************************************************
	*  Format a mySQL string correctly for safe mySQL insert
	*  (no mater if magic quotes are on or not)
	*/

	function escape($str)
	{
		// If there is no existing database connection then try to connect
		if ( ! isset($this->dbh) || ! $this->dbh )
		{
			$this->connect($this->dbuser, $this->dbpassword, $this->dbhost);
			$this->select($this->dbname, $this->encoding);
		}

		return mysql_real_escape_string(stripslashes($str));
	}

	/**********************************************************************
	*  Return mySQL specific system date syntax
	*  i.e. Oracle: SYSDATE Mysql: NOW()
	*/

	function sysdate()
	{
		return 'NOW()';
	}

	/**********************************************************************
	*  Perform mySQL query and try to detirmin result value
	*/

	function query($query)
	{

		// This keeps the connection alive for very long running scripts
		if ( $this->num_queries >= 500 )
		{
			$this->num_queries = 0;
			$this->disconnect();
			$this->quick_connect($this->dbuser,$this->dbpassword,$this->dbname,$this->dbhost,$this->encoding);
		}

		// Initialise return
		$return_val = 0;

		// Flush cached values..
		$this->flush();

		// For reg expressions
		$query = trim($query);

		// Log how the function was called
		$this->func_call = "\$db->query(\"$query\")";

		// Keep track of the last query for debug..
		$this->last_query = $query;

		// Count how many queries there have been
		$this->num_queries++;

		// Start timer
		$this->timer_start($this->num_queries);

		// Use core file cache function
		if ( $cache = $this->get_cache($query) )
		{
			// Keep tack of how long all queries have taken
			$this->timer_update_global($this->num_queries);

			// Trace all queries
			if ( $this->use_trace_log )
			{
				$this->trace_log[] = $this->debug(false);
			}

			return $cache;
		}

		// If there is no existing database connection then try to connect
		if ( ! isset($this->dbh) || ! $this->dbh )
		{
			$this->connect($this->dbuser, $this->dbpassword, $this->dbhost);
			$this->select($this->dbname,$this->encoding);
			// No existing connection at this point means the server is unreachable
			if ( ! isset($this->dbh) || ! $this->dbh )
				return false;
		}

		// Perform the query via std mysql_query function..
		$this->result = @mysql_query($query,$this->dbh);

		// If there is an error then take note of it..
		if ( $str = @mysql_error($this->dbh) )
		{
			$this->register_error($str);
			$this->show_errors ? trigger_error($str,E_USER_WARNING) : null;
			echo '<pre>';
			debug_print_backtrace();
			echo '</pre>';
			return false;
		}

		// Query was an insert, delete, update, replace
		if ( preg_match("/^(insert|delete|update|replace|truncate|drop|create|alter|set)\s+/i",$query) )
		{
			$is_insert = true;
			$this->rows_affected = @mysql_affected_rows($this->dbh);

			// Take note of the insert_id
			if ( preg_match("/^(insert|replace)\s+/i",$query) )
			{
				$this->insert_id = @mysql_insert_id($this->dbh);
			}

			// Return number fo rows affected
			$return_val = $this->rows_affected;
		}
		// Query was a select
		else
		{
			$is_insert = false;

			// Take note of column info
			$i=0;
			while ($i < @mysql_num_fields($this->result))
			{
				$this->col_info[$i] = @mysql_fetch_field($this->result);
				$i++;
			}

			// Store Query Results
			$num_rows=0;
			while ( $row = @mysql_fetch_object($this->result) )
			{
				// Store relults as an objects within main array
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			@mysql_free_result($this->result);

			// Log number of rows the query returned
			$this->num_rows = $num_rows;

			// Return number of rows selected
			$return_val = $this->num_rows;
		}

		// disk caching of queries
		$this->store_cache($query,$is_insert);

		// If debug ALL queries
		$this->trace || $this->debug_all ? $this->debug() : null ;

		// Keep tack of how long all queries have taken
		$this->timer_update_global($this->num_queries);

		// Trace all queries
		if ( $this->use_trace_log )
		{
			$this->trace_log[] = $this->debug(false);
		}

		return $return_val;

	}

	/**********************************************************************
	*  Close the active mySQL connection
	*/

	function disconnect()
	{
		@mysql_close($this->dbh);	
	}

} 