<?php

// require connection parametrs
require_once '../common.php';

// drop all database tables
#$db->drop('confirm');

//
$schema = [

    // define users table
    'User' => [
        'user_id'  => $db::PRIMARY_KEY,
        'password' => '<<string 10>>',
        'type'     => 2,
        'username' => '',
        'tipe'     => $db::INT_20,
        'bom'      => ['a','b','t'],
    ],

    // define articles table
    'Article' => [
        'article_id' => $db::PRIMARY_KEY,
        'title'      => '',
        'content'    => $db::DATE,
    ],
];

// Apply schema create or update database tables
$db->apply($schema);

//
$db->dump();

// 
$db->benchmark();