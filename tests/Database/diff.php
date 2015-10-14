<?php

// title
echo '<h1>Database desc with var_dump</h1>';

// require connection parametrs
require_once '../common.php'; 

// require library
require_once '../../src/Javanile/SchemaDB/autoload.php';

// retrive main class to enstablish connection
use Javanile\SchemaDB;

// Connect to MySQL database
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't100_',
));

$schema = array(
	
	// define users table
	'User' => array(
		'userid'   => $db::VARCHAR,
		'username' => '',
		'password' => 0,		
	),
	
	// define articles table
	'Article' => array(
		'articleid' => 0,
		'title'		=> '',
		'content'	=> '',
	),	
);

//
#$db->apply($schema);

// Compare schema and generate SQL to update tables
$diff = $db->diff($schema);

//
echo '<pre style="padding:2px;background:#eee;border:1px solid #ccc;">';
var_dump($diff);
echo '</pre>';

// 
$db->benchmark();