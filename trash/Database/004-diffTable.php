<?php

//
echo '<h1>Diff single table</h1>';

// require connection parametrs
require_once 'common.php'; 

// retrive main class to enstablish connection
use Javanile\SchemaDB;

//
$fields = array(
    'userid'   => 1,
    'username' => '',
    'password' => '',
);

//
$db->applyTable('User', $fields);

// Apply schema create or update database tables
$diff = $db->diffTable('User', $fields);

//
SchemaDB\Debug::varDump($diff);

//
$db->benchmark();


