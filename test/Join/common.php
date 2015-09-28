<?php

##
require_once '../common.php'; 

##
require_once '../../SchemaDB.php';

##
use SourceForge\SchemaDB\SchemaDB;

##
use SourceForge\SchemaDB\Storable;

##
new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't101_',
));