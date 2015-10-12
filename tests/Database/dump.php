<?php

##
echo '<h1>Print-out database schema</h1>';

##
require_once '../common.php';

## require library
require_once '../../src/Javanile/SchemaDB/autoload.php';

##
use Javanile\SchemaDB;

##
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't100_',
));

##
$db->dump();

##
$db->benchmark();