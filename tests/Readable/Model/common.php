<?php

//
require_once '../common.php'; 

//
require_once '../../src/SourceForge/SchemaDB/autoload.php';

//
use SourceForge\SchemaDB;

//
new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't105_',
));
