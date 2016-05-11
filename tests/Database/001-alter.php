<?php

// require connection parametrs
require_once 'common.php'; 

// drop all database tables
#$db->drop('confirm');

// Alter schema create or update database tables
$db->alter([
    
    // define users table
    'People' => [
        'name' => $db::TIME,
    ],
]);

// print-out schema
$db->info(['People', 'Robot']);

// print-out debug info
$db->benchmark();