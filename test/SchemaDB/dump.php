<?php

##
require_once '../common.php';

##
require_once '../../SchemaDB.php';

##
use SourceForge\SchemaDB\SchemaDB;

##
$SchemaDB = new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't100_',
));

##
$SchemaDB->dump();

