<?php

##
error_reporting(E_ALL);
ini_set('display_errors',true);
if (function_exists('xdebug_disable')) { xdebug_disable(); }

## 
require_once '../../SchemaDB.php';

##
use SourceForge\SchemaDB\SchemaDB;

##
$conn = new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => $pref,
));

##
class Persona extends Storable {			
	public $field2 = static::PRIMARY_KEY;	
	public $id14 = 9;
	public $field1 = 5;
	public $ciccio = "";
}

Persona::make(array('ciccio'=>'ciao'))->store();

##
Persona::desc();

##
Persona::dump();


