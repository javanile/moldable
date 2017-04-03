<?php

// title
echo '<h1>Database diff with var_dump</h1>';

// require connection parametrs
require_once 'common.php';

//
use Javanile\SchemaDB;

//
$schema = array(
    
    // define users table
    'User' => array(
        'userid'   => $db::VARCHAR,
        'username' => '',
        'password' => 0,        
    ),
    
    // define articles table
    'Article' => array(
        'articleid' => 0,
        'title'        => '',
        'content'    => '',
    ),    
);

//
#$db->apply($schema);

// Compare schema and generate SQL to update tables
$diff = $db->diff($schema);

//
SchemaDB\Debug::varDump($diff);

// 
$db->benchmark();