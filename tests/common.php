<?php

//
error_reporting(E_ALL);
ini_set('display_errors',true);
if (function_exists('xdebug_disable')) { xdebug_disable(); }

//
define('__BASE__', dirname(dirname(dirname(dirname(__DIR__)))));

//
echo '<style>pre{padding:0;margin:0;}</style>';

//
if (!file_exists(__DIR__.'/override.php')) {

	//
	$host = '<<host>>';	// database host 
	$user = '<<user>>';	// database username
	$pass = '<<pass>>';	// database password
	$name = '<<name>>';	// database name	
	$pref = '<<pref>>'; // database table prefix
} 

//
else {
	require_once __DIR__.'/override.php';
}

