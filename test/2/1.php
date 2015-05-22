<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once '../../schemadb.php';

$conn = "";

schemadb::connect($conn,$user,$pass,$pref);

class People extends sdbClass {
	
	public $pid = MYSQL_PRIMARY_KEY;
	public $name = "";
	public $surname = "";
	public $age = 0;
	public $ciccio = 0;
	
}

People::schemadb_update();