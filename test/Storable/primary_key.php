<?php

##
require_once 'common.php'; 

##
use SourceForge\SchemaDB\SchemaDB;

##
use SourceForge\SchemaDB\Storable;

##
class Person extends Storable {
	
	##
	public $id = self::PRIMARY_KEY;
}

//Person::drop('confirm');

##
$Person = new Person();

##
$Person->name = "Ciao";

##
$Person->store();

##
Person::dump();