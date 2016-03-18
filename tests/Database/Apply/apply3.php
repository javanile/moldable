<?php

// require connection parametrs
require_once '../common.php';

// drop all database tables
// $db->drop('*','confirm');

//
$notations = [
    '',
    0,
    .0,
    $db::VARCHAR_10
];

//
foreach ($notations as $notation) {
    $db->apply('User', 'entryrole', $notation);
}

//
$db->dump();

// 
$db->benchmark();