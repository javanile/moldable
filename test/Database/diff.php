<?php

## require connection parametrs
require_once '../common.php'; 

## require library
require_once '../../src/SourceForge/SchemaDB/autoload.php';

## retrive main class to enstablish connection
use SourceForge\SchemaDB;

## Connect to MySQL database
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't100_',
));

## Compare schema and generate SQL to update tables
$diff = $db->diff(array(
	
	## define users table
	'User' => array(
		'userid'   => SchemaDB\Table::VARCHAR,
		'username' => '',
		'password' => '',		
	),
	
	## define articles table
	'Article' => array(
		'articleid' => 0,
		'title'		=> '',
		'content'	=> '',
	),	
));

##
echo '<pre>';
var_dump($diff);
echo '</pre>';