<?php

//
require_once '../common.php'; 

//
require_once '../../src/Javanile/SchemaDB/autoload.php';

//
use Javanile\SchemaDB;

//
new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 'Test_Join_',
));

//
class Person extends SchemaDB\Storable {
	
	//
	public $id = self::PRIMARY_KEY;	 

	//
	public $name = "";
	public $surname = "";
	public $age = 0;
	public $address1 = 0;	
	public $address2 = 0;	
}

//
class Address extends SchemaDB\Storable {

	//
	public $id = self::PRIMARY_KEY;
	
	//
	public $name = "";
	public $latitude = 0;
	public $longitude = 0;
	public $city = "";
}