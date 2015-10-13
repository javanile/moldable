<?php

##
require_once 'common.php'; 

##
use Javanile\SchemaDB;

##
class Invoices extends SchemaDB\Storable {
	
	##
	public $id = self::PRIMARY_KEY; ## 
	
	##
	public $number = 0;
	
	##
	public $year = 0;
	
	##
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