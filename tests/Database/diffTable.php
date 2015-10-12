<?php

##
echo '<h1>Diff single table</h1>';

## require connection parametrs
require_once '../common.php'; 

## require library
require_once '../../src/Javanile/SchemaDB/autoload.php';

## retrive main class to enstablish connection
use Javanile\SchemaDB;

## Connect to MySQL database
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user, 
	'pass' => $pass,
	'name' => $name,
	'pref' => 't100_',
));

## Apply schema create or update database tables
$diff = $db->diffTable('User', array(
	'userid'   => 1,
	'username' => '', 
	'password' => '',		
));

##
SchemaDB\Debug::var_dump($diff);

##
$db->benchmark();


