<?php

##
require_once '../common.php'; 

##
require_once '../../src/SourceForge/SchemaDB/autoload.php';

##
use SourceForge\SchemaDB;

##
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't1_',
));
