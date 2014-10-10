<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once('../../schemadb.php');

$host = 'localhost';	## database host 
$user = 'root';			## database username
$pass = 'root';			## database password
$name = 'db_schemadb';	## database name	
$pref = 'pref_';		## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

$schema = schemadb::schema_parse(array(
	'user' => array(
		'id'	=> MYSQL_PRIMARY_KEY,
		'name'	=> MYSQL_VARCHAR_80,
 	),	
	'post' => array(
		'id'	=> MYSQL_PRIMARY_KEY,
		'title' => MYSQL_VARCHAR_255,
		'body'	=> MYSQL_TEXT,
	),
));

schemadb::apply($schema);

schemadb::dump();