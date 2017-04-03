<?php

//
require_once 'common.php';

//
use SourceForge\SchemaDB\Storable;

//
class Person extends Storable
{    
    //
    public $id = self::PRIMARY_KEY;
    
    //
    public $name = "";
    
    //
    public $age = 0;    
}

// remove Person table and complete items list
Person::drop('confirm');

// import Persons from array-of-array
Person::import(array(
    array('name' => 'Francesco',    'age' => 10),
    array('name' => 'Paolo',        'age' => 12),
    array('name' => 'Piero',        'age' => 10),
    array('name' => 'Antonio',        'age' => 13),    
));

// printout table record before delete
Person::dump();

// delete Person with 10 years old 
Person::delete(array('age' => 10));

// printout table record after delete
Person::dump();
