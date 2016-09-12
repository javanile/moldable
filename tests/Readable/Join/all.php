<?php

//
require_once '_common.php';

//
use Javanile\SchemaDB\Readable;

//
class Person extends Readable 
{   
    //
    public $id = self::PRIMARY_KEY;     

    //
    public $name     = "";
    public $surname  = "";
    public $age      = 0;
    public $address1 = 0;    
    public $address2 = 0;    
}

//
class Address extends Readable 
{
    //
    public $id = self::PRIMARY_KEY;
    
    //
    public $name      = "";
    public $latitude  = 0;
    public $longitude = 0;
    public $city      = "";
}

//
$Persons = Person::all([
    'name',    
    'address' => Address::join(),
]);

//
Person::dump($Persons);
