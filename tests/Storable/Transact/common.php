<?php

//
require_once '../common.php'; 

//
require_once '../../SchemaDB.php';

//
use SourceForge\SchemaDB\SchemaDB;

//
new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 'Test_Join_',
));

//
use SourceForge\SchemaDB\Storable;

//
class Person extends Storable {
	
	//
	public $id = self::PRIMARY_KEY;	 

	//
	public $name = "";
	public $surname = "";
	public $age = 0;
	public $address = 0;	
}

//
class Address extends Storable {

	//
	public $id = self::PRIMARY_KEY;
	
	//
	public $name = "";
	public $latitude = 0;
	public $longitude = 0;
}