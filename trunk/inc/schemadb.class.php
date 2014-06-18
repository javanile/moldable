<?php

// static part of sdbClass
class schedadb_class_static {
	
	//
	public static $out_of_schema = array(
		'class',
		'table',
		'cache',
		'out_of_schema',
	);

	// retrieve table name
	public static function table() {
		$p = schemadb_action('prefix');
		if (isset(static::$table)) {
			$t = static::$table; 
		} else if (isset(static::$class)) {
			$t = static::$class; 
		} else {
			$o = new static();
			$t = get_class($o);			
		}
		return $p.$t;
	}
	
	// retrieve static class name
	public static function get_static_class() {
		if (isset(static::$class)) {
			$c = static::$class; 
		} else {
			$o = new static();
			$c = get_class($o);			
		}
		return $c;
	}
	
	// load element by primary key
	public static function load($id) {
		$t = static::table();
		$k = static::primary_key();
		$s = "SELECT * FROM {$t} WHERE {$k}='{$id}' LIMIT 1";
		$r = schemadb_action('row',$s);
		$o = static::build($r);
		return $o;
	}
	
	//
	public static function dummy() {
		$c = static::get_static_class();		
		$o = new $c();
		return $o;		
	}
	
	// 
	public static function build($array) {
		$o = new static();
		$o->fill($array);
		return $o;
	}
		
	//
	public static function all() {
		$t = self::table();		
		$s = "SELECT * FROM {$t}";
		$r = schemadb_action('results',$s);
		$a = array();
		foreach($r as $i=>$o) {
			$a[$i] = self::build($o);
		}
		return $a;
	}
	
	//
	public static function query($query) {
		$t = self::table();		
		
		## where block for the query
		$w = array();
		if (isset($query['where'])) {
			$w[] = $query['where'];
		}
		foreach($query as $k=>$v) {
			if ($k!='sort'&&$k!='where') {
				$w[] = "{$k}='$v'";
			}
		}
		$w = count($w)>0 ? 'WHERE '.implode(' AND ',$w) : '';
		
		## order by block
		$o = isset($query['sort']) ? 'ORDER BY '.$query['sort'] : '';		
		
		## build query
		$q = "SELECT * FROM {$t} {$w} {$o}";				
		
		## fetch res
		$r = schemadb_action('results',$q);
		$a = array();
		foreach($r as $i=>$o) {
			$a[$i] = self::build($o);
		}
		return $a;
	}
	
	//
	public static function ping($array) {
		$t = self::table();		
		$w = array();
		foreach($array as $k=>$v) {
			$w[] = "{$k}='{$v}'";			
		}
		$w = count($w)>0 ? 'WHERE '.implode(' AND ',$w) : '';
		$s = "SELECT * FROM {$t} {$w} LIMIT 1";
		$r = schemadb_action('row',$s);		
		if ($r) {
			return self::build($r);
		}		
	}
		
	//
	public static function dump() {
		$a = static::all();
		echo '<table border=1>';
		foreach($a as $r) {			
			echo '<tr>';
			foreach($r as $f=>$v) {
				echo '<td>'.$v.'</td>';
			}				
			echo '</tr>';
		}
		echo '</table>';
	}
	
	// delete element by primary key
	public static function delete($id) {
		if ($id>0) {
			$t = static::table();
			$k = static::primary_key();
			$s = "DELETE FROM {$t} WHERE Id='$id'";
			schemadb_action('query',$s);
		}		
	}		
	
	// instrospect and retrieve element schema
	public static function schema() {		
		$c = static::get_static_class();		
		$f = get_class_vars($c);
		$t = array();
		foreach($f as $k=>$v) {
			if (!in_array($k,self::$out_of_schema)) {
				$t[$k] = $v;
			}
		}
		return $t;
	}
	
	
	// update db table based on class schema
	public static function schemadb_update() {
		
		$s = array();
		$t = static::table();		
		$e = static::schema();
			
		foreach($e as $f=>$v) {
			$s[$f] = schemadb_define($v);									 
		}			
		
		if (count($s)>0) {
			schemadb_table_update($t,$s);
		}		
	}
}

// self methods of sdbClass
class schemadb_class extends schedadb_class_static {
			
	// constructor
	public function __construct() {
		foreach($this->fields() as $f) {
			$this->{$f} = schemadb_pseudotype_value($this->{$f});
		}
	}
			
	// self-store element method
	public function store() {				
		static::schemadb_update();	
		
		$k = static::primary_key();		
				
		if ($k && $this->{$k}>0) {
			return $this->update();						
		} else {
			return $this->insert();			
		}
	}
		
	// fill field with set parser value from array
	public function fetch($array) {			
		
		foreach($this->fields() as $f) {
			$this->set($f,$array[$f]);			
		}
		
		$k = $this->primary_key();
		
		if ($k) {
			$this->{$k} = isset($array[$k]) ? (int) $array[$k] : (int)$this->{$k}; 		
		}				
	}
	
	//
	public function fill($array) {		
		foreach($this->fields() as $f) {
			$this->{$f} = $array[$f];			
		}
		
		$k = $this->primary_key();
		
		if ($k) {
			$this->{$k} = isset($array[$k]) ? (int) $array[$k] : (int)$this->{$k}; 		
		}	
	}
	
	
	public function update() {		
		
		$k = static::primary_key();
		$s = array();
				
		foreach($this->fields() as $f) {
			if ($f!=$k) {
				$v = $this->{$f};
				$s[] = $f." = '".$v."'";
			}
		}
		$s = implode(',',$s);

		$t = $this->table();
		$i = $this->{$k};
		$q = "UPDATE {$t} SET {$s} WHERE {$k}='{$i}'";
		
		schemadb_action('query',$q);		
	}
	
	public function insert() {		
		
		$c = array();
		$v = array();
		$k = static::primary_key();
		
		foreach($this->fields() as $f) {
			if ($f!=$k) {	
				$a = $this->{$f};
				$c[] = $f;			
				$v[] = "'".$a."'";
			}
		}
		
		$c = implode(',',$c);
		$v = implode(',',$v);
		
		$t = $this->table();
		$q = "INSERT INTO {$t} ($c) VALUES ($v)";
			
		$r = schemadb_action("query",$q);
		
		if ($k) {
			$i = schemadb_action("last_id");	
			$this->{$k} = $i;
		}
			
		return $r;
	}
	
	// return fields names
	public function fields() {		
		$c = get_class($this);
		$f = get_class_vars($c);
		$a = array();
		foreach($f as $k=>$v) {
			if (!in_array($k,self::$out_of_schema)) {
				$a[] = $k;
			}
		}
		return $a;		
	}
			
	public static function primary_key() {
		$s = static::schema();		
		foreach($s as $k=>$v) {			
			if ($v === MYSQL_PRIMARY_KEY) {
				return $k;
			}
		}
		return false;
	}
	
	public function get($field) {
		$m = 'get_parser_'.$field;
		if (method_exists($this,$m)) {
			return $this->{$m}($this->{$field});		
		} else {
			return $this->{$field};
		}
	}
	
	public function set($field,$value) {
		$m = 'set_parser_'.$field;
		if (method_exists($this,$m)) {
			$this->{$field} = $this->{$m}($value);
		} else {
			$this->{$field} = $value;
		}
	}	
} 

// canonical name
class sdbClass extends schemadb_class {
	// .
	// .
	// .
}
