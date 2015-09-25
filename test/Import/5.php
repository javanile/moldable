<?php
ini_set('display_errors',true);
error_reporting(E_ALL);

require_once '../schemadb.php';

$schema = array(
	
	'Table1' => array(
		'Field1' => 0,
		'Field2' => MYSQL_DATETIME,
	),
	
	'Table2' => array(
		'Field1' => MYSQL_,
		'Field2' => MYSQL_TIME,
	)
	
);

echo '<pre>';
$s = schemadb::table_schema_parse($schema['Table1']);
var_dump($s);

