<?php

//
require_once 'common.php'; 

//
use Javanile\SchemaDB\Storable;

// extremely coincise model definition
class Invoices extends Storable 
{	
	// with a private key field
	public $id = self::PRIMARY_KEY;
	
	// . . . 
	public $number = 0;
		
	// . . .
	public $created = self::DATE;
}

//
$Person = new Person();

//
$Person->name = "Ciao";

//
$Person->store();

//
Person::dump();