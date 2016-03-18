<?php

// require connection parametrs
require_once '../common.php';

// drop all database tables
$db->drop('*', 'confirm');

//
$db->apply('User', 'entryrole', 0);

//
$db->dump();

// 
$db->benchmark();