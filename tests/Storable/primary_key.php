<?php

##
require_once 'common.php'; 

##
use Javanile\SchemaDB;

## extremely coincise model definition
class Invoices extends SchemaDB\Storable 
{	
	## with a private key field
	public $id = self::PRIMARY_KEY;
	
	## . . . 
	public $number = 0;
		
	## . . .
	public $created = self::DATE;
}






##
$Person = new Person();

##
$Person->name = "Ciao";

##
$Person->store();

##
Person::dump();