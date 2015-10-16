<?php

// require connection parametrs
require_once 'common.php'; 

// drop all database tables
$db->drop('confirm');

// Alter schema create or update database tables
$db->alter(array(
	
	// define users table
	'User' => array(		
		'name' => '',
	),
));

//
$db->dump();

// 
$db->benchmark();