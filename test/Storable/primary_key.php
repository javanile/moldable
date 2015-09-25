<?php

##
require_once 'common.php'; 

##
use SourceForge\SchemaDB\Storable;

##
class Person extends Storable {
	
	##
	public $id = self::PRIMARY_KEY;
}

##
$Person = new Person();

##
$Person->name = "Ciao";

##
$Person->store();

##
Person::dump();