<?php

//
require_once 'common.php';

// import Persons from array-of-array
$db->import('People', array(
    array('name' => 'Francesco',    'age' => 10),
    array('name' => 'Paolo',        'age' => 12),
    array('name' => 'Piero',        'age' => 10),
    array('name' => 'Antonio',        'age' => 13),    
));

// printout table record before delete
$db->dump('People');

// delete Person with 10 years old 
$Person = $db->exitsts('Person', array('age' => 10));

//
SchemaDB\Debug::varDump($Person);