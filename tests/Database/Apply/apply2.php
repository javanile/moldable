<?php

// require connection parametrs
require_once 'common.php'; 

// drop all database tables
#$db->drop('confirm');

// define users table
$db->apply('User', array(
    'userid'	=> $db::PRIMARY_KEY,
    'password'	=> '',
    'type'		=> 1,
    'username'	=> '',
    'tipe'		=> $db::INT_20,
));

//
$db->dump();

// 
$db->benchmark();