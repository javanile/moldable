<?php

//
require_once 'common.php'; 

//
use Javanile\SchemaDB\Storable;

//
class Person extends Storable {
    
}

//
$Person = new Person();

// update before store
$Person->store([
    'name' => 'Frank',
]);

//
Person::dump();
