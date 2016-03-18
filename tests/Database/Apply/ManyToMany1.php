<?php

// require connection parametrs
require_once '../common.php';

// drop all database tables
$db->drop('*', 'confirm');

//
$db->apply([
    'Table1' => [
        'Field1' => '',
        'Field2' => '<<Table2**>>',
    ]
]);

//
$db->dump();

// 
$db->benchmark();