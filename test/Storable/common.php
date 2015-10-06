<?php

##
require_once '../common.php'; 

##
use SourceForge\SchemaDB;

##
new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 's1_',
));

