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

## Apply schema create or update database tables
$diff = $db->updateTable('User', array(
	'userid'   => 1,
	'username' => '',
	'password' => '',		
));

##
echo '<pre>';
var_dump($diff);
echo '</pre>';