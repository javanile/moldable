<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once '../schemadb.php';

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 'd1_';					## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

schemadb::apply(array(
	
	'table1' => array(
		'id'		=> MYSQL_PRIMARY_KEY,
		'valore'	=> true,
		'number'	=> 10,
		'title'		=> MYSQL_VARCHAR,	
		'title2'	=> '%|varchar(200)|%',
		'cap'		=> array(1,2,3),
		'rela'		=> '<<People>>',
	),

)); 
 
schemadb::dump();

