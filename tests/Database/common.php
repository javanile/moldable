<?php

//
require_once '../common.php'; 

//
require_once '../../src/Javanile/SchemaDB/autoload.php';

//
use Javanile\SchemaDB;

//
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't1_',
));
