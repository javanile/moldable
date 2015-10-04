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

## drop all database tables
#$db->drop('confirm');

## Apply schema create or update database tables
$db->apply(array(
	
	## define users table
	'User' => array(
		'userid'   => SchemaDB\Table::PRIMARY_KEY,
		'username' => '',
		'password' => '',
		'type'	=> 1
	),
	
	## define articles table
	'Article' => array(
		'articleid' => SchemaDB\Table::PRIMARY_KEY,
		'title'		=> '',
		'content'	=> '',
	),	
));

##
$db->dump();
