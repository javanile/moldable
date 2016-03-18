<?php

// require connection parametrs
require_once '../common.php';

// drop all database tables
$db->drop('*', 'confirm');

// define users table
$db->apply('User', [
    'userid'	=> '<<primary key int 10>>',
    'password'	=> '',
    'type'		=> 1,
    'username'	=> '',
    'tipe'		=> $db::INT_20,
    'floatas'   => '<<int 10>>',
]);

//
$db->dump();

// 
$db->benchmark();
