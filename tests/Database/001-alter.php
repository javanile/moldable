<?php

// require connection parametrs
require_once 'common.php'; 

// drop all database tables
#$db->drop('confirm');

// Alter schema create or update database tables
$db->alter([
    
    // define users table
    'User' => [
        'name' => 1,
    ],
]);

// print-out schema
$db->info('User');

// print-out debug info
$db->benchmark();