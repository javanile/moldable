<?php

//
require_once 'common.php';

//
use Javanile\SchemaDB;

//
$db->apply(array(
    'People' => array(
        'name' => '',
        'age'  => 0,
    ),
));

//
$db->alter(array(
    'People' => array(
        'name' => $db::TEXT,
    ),
));

// remove Person table and complete items list
$db->drop('People', 'confirm');

// import Persons from array-of-array
$db->import('People', array(
    array('name' => 'Francesco', 'age' => 10),
    array('name' => 'Paolo',     'age' => 12),
    array('name' => 'Piero',     'age' => 10),
    array('name' => 'Antonio',     'age' => 13),
));

// printout table record before delete
$db->dump();

// delete Person with 10 years old 
$Person = $db->exists('People', array(
    'age' => 13,
    '@where' => "name LIKE '{Fra}%'",
    '@limit' => 10,
));

// 
SchemaDB\Debug::varDump($Person);