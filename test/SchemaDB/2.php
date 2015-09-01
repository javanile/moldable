<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once '../../schemadb.php';

$host = 'localhost';	## database host 
$user = 'root';			## database username
$pass = 'root';			## database password
$name = 'db_schemadb';	## database name	
$pref = 't32_';			## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

class TestModel extends sdbClass {	
	
	static $table = 'tm';
	
	public $pid = MYSQL_PRIMARY_KEY;
	public $sta = ['a','b','c'];
	
}

TestModel::schemadb_update();

schemadb::dump();
