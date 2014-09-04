<?php
ini_set('display_errors',true);
error_reporting(E_ALL);

require_once('../schemadb.php');

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 't6_';					## table prefix

schemadb::debug(true);

schemadb::connect($host,$user,$pass,$name,$pref);

schemadb::apply(array(
	't1' => array(
		'f1'
	),	
));

schemadb::dump();

