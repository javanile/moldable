<?php

//
require_once 'common.php';

//
use Javanile\SchemaDB\Storable;

//
class Person extends Storable
{	
	//
	public $id = self::PRIMARY_KEY;
	
	//
	public $name = '';
	
	//
	public $age = 0;	
}

// update record with id=10 
echo Person::update(10, ['age' => 21]);

// update all record where age = 11
echo Person::update(['age' => 11], ['age' => 21]);

// update all record where age > 10
echo Person::update(['where' => 'age > 10'], ['age' => 21]);

