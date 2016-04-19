<?php

//
error_reporting(E_ALL);
ini_set('display_errors',true);

//
require_once 'common.php'; 

// 
require_once '../../src/SourceForge/SchemaDB/autoload.php';

//
use SourceForge\SchemaDB;

//
$db = new SchemaDB\Database(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => $pref,
));

//
class Person extends SchemaDB\Storable {
    
    //
    public $id = self::PRIMARY_KEY;
    
    //
    public $name = "";
    
    //
    public $age = 0;    
    
    //
    public $telephone = "";        
}

// remove Person table and complete items list
Person::drop('confirm');

// import Persons from array-of-array
Person::import(array(
    array('name' => 'Francesco',    'age' => 10),
    array('name' => 'Paolo',        'age' => 12),
    array('name' => 'Matteo',        'age' => 15),
    array('name' => 'Piero',        'age' => 10),
    array('name' => 'Antonio',        'age' => 13),    
    array('name' => 'Carlo',        'age' => 9),    
));

// printout table record before delete
Person::dump();

//
$list = Person::query(array(
    'where' => "age > 11",
    'order' => "age ASC",
    'limit' => "2",
));

//
Person::dump($list);


