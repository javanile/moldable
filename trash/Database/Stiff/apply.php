<?php

## require connection parametrs
require_once 'common.php'; 

## drop all database tables
#$db->drop('confirm');

## Apply schema create or update database tables
$db->apply(array( 
	
	## define users table
	'User' => array(
		'userid'	=> $db::PRIMARY_KEY,
		'password'	=> '',
		'type'		=> 1,
		'username'	=> '',
		'tipe'		=> $db::INT_20,
	),
	
	## define articles table
	'Article' => array(
		'articleid' => $db::PRIMARY_KEY,
		'title'		=> '',
		'content'	=> '',
	),	
));

##
$db->dump();

## 
$db->benchmark();