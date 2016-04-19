<?php

//
error_reporting(E_ALL);
ini_set('display_errors',true);

//
require_once '../data.php'; 

//
require_once '../../SchemaDB.php';

//
use SourceForge\SchemaDB\SchemaDB;

//
use SourceForge\SchemaDB\Storable;

//
new SchemaDB(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => $pref,
));

//
class Person extends Storable {
    
    //
    public $id = self::PRIMARY_KEY;
    
    //
    public $name = "";
    
    //
    public $age = 0;    
}

// update record with id=10 
echo Person::update(10, array('age' => 21));

// update all record where age = 11
echo Person::update(array('age' => 11), array('age' => 21));

// update all record where age > 10
echo Person::update(array('where' => 'age > 10'), array('age' => 21));

