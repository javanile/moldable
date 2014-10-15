<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once '../../schemadb.php';

$host = 'localhost';	## database host 
$user = 'root';			## database username
$pass = 'root';			## database password
$name = 'db_schemadb';	## database name	
$pref = 't33_';			## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

class TestModel extends sdbClass {	
	
	public $pid = MYSQL_PRIMARY_KEY;
	public $sta = false;	
	public $dat = '2010-10-10';
	
	public static function encode_dat($dat) {
		return $dat;
	}
	
}


$data = TestModel::decode($_POST);
		TestModel::encode($o);

