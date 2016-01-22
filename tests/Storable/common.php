<?php

//
require_once '../common.php'; 

//
require_once __DIR__.'/../../../../autoload.php';

//
use Javanile\SchemaDB;

// 
new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 's1_',
));

