<?php

// require connection parametrs
require_once '../common.php';

// drop all database tables
#$db->drop('*', 'confirm');

//
$db->apply('Model', 'field', '<<primary key 10>>');

//
$db->dump();

// 
$db->benchmark();