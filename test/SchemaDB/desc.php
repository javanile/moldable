<?php

## require connection parametrs
require_once '../common.php'; 

## require library
require_once '../../SchemaDB.php';

## retrive main class to enstablish connection
use SourceForge\SchemaDB\SchemaDB;

## Connect to MySQL database
$Schema = new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 't100_',
));

## Apply schema create or update database tables
$desc = $Schema->desc();

##
echo '<pre>';
var_dump($desc);
echo '</pre>';
