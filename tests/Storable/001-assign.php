<?php

//
require_once 'common.php'; 

//
use Javanile\SchemaDB\Storable;

//
class Person extends Storable {
    var $name = '';
    var $age = 0;
}

//
$Person = new Person();

// update before store
$Person->store([
    'name' => 'Frank',
]);

//
Person::dump();
