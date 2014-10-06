<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once('../../schemadb.php');

$schema = schemadb::schema_parse(array(
	'user' => array(
		'id'	=> MYSQL_PRIMARY_KEY,
		'name'	=> MYSQL_VARCHAR_80,
 	),		
));

echo '<pre>';
var_dump($schema);
echo '</pre>';
