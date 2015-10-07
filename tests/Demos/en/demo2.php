<?php
require_once('../schemadb.php');

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 'demo2_';				## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

schemadb_apply(array(
	
	'table1' => array(
		'id',
	),
	
	'table2' => array(
		'id',
	),
	
));