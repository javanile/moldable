<?php

//
error_reporting(E_ALL);
ini_set('display_errors',true);
if (function_exists('xdebug_disable')) { xdebug_disable(); }

//
require_once __DIR__.'/../common.php';

//
require_once __DIR__.'/../../../../autoload.php';

//
use Javanile\SchemaDB\Database;

//
$db = new Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't1_',
));

//
$db->setDebug(true);

