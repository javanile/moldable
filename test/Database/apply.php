<?php

## require connection parametrs
require_once 'common.php'; 

##
use SourceForge\SchemaDB;

## drop all database tables
#$db->drop('confirm');

## Apply schema create or update database tables
$db->apply(array( 
	
	## define users table
	'User' => array(
		'userid'	=> SchemaDB\ModelFields::PRIMARY_KEY,
		'username'	=> '',
		'password'	=> '',
		'type'		=> 1,
		'tipe'		=> SchemaDB\ModelFields::INT_10,
	),
	
	## define articles table
	'Article' => array(
		'articleid' => SchemaDB\ModelFields::PRIMARY_KEY,
		'title'		=> '',
		'content'	=> '',
	),	
));

##
$db->dump();
