<?php

##
require_once '../common.php';

## require library
require_once '../../src/SourceForge/SchemaDB/autoload.php';

##
use SourceForge\SchemaDB;

##
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't100_',
));

##
if ($db->tableExists('Project')) {
	echo 'Table already exists!';
} else {
	echo 'No table found.';
}
