<?php

//
error_reporting(E_ALL);
ini_set('display_errors',true);
if (function_exists('xdebug_disable')) { xdebug_disable(); }

// require connection parametrs
require_once '../common.php';

// drop all database tables
#$db->drop('confirm');

//
$schema = array(

	// define users table
	'User' => array(
		'userid'	=> $db::PRIMARY_KEY,
		'password'	=> '',
		'type'		=> 1,
		'username'	=> '',
		'tipe'		=> $db::INT_20,
	),

	// define articles table
	'Article' => array(
		'articleid' => $db::PRIMARY_KEY,
		'title'		=> '',
		'content'	=> '',
	),
);

// Apply schema create or update database tables
$db->apply($schema);

//
$db->dump();

// 
$db->benchmark();