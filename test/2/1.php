<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once '../../schemadb.php';

$host = 'localhost';	## database host 
$user = 'root';			## database username
$pass = 'root';			## database password
$name = 'db_schemadb';	## database name	
$pref = 'pref_';		## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

schemadb::apply([
	'user' => [
		'id0'	=> MYSQL_PRIMARY_KEY,
		'id1'	=> 0,
		'code' => 0,
		'name' => '',
	]
]);

schemadb::dump();
