<?php

##
error_reporting(E_ALL);
ini_set('display_errors',true);

##
require_once '../data.php'; 

##
require_once '../../SchemaDB.php';

##
new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => $pref,
));

##
class Item extends sdbClass {
	
	public $id = sdbClass::PRIMARY_KEY;
	
	public $name = "";
	
	public $age = 0;	
}

##
Item::drop();

##
Item::import(array(
	array('name' => 'Francesco',	'age' => 10),
	array('name' => 'Paolo',		'age' => 12),
	array('name' => 'Piero',		'age' => 10),
	array('name' => 'Antonio',		'age' => 13),	
));

##
Item::dump();

##
Item::delete(array('age' => 10));

##
Item::dump();



