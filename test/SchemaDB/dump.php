<?php

##
error_reporting(E_ALL);
ini_set('display_errors',true);

##
require_once '../data.php';

##
require_once '../../SchemaDB.php';

##
use SourceForge\SchemaDB\SchemaDB;

##
$conn = new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => $pref,
));

##
$conn->dump();

