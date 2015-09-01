<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
if (function_exists('xdebug_disable')) { xdebug_disable(); }

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
class Persona extends sdbClass {			
	public $field2 = sdbClass::PRIMARY_KEY;	
	public $id14 = 9;
	public $field1 = 5;
	public $ciccio = "";
}

Persona::make(array('ciccio'=>'ciao'))->store();

##
Persona::desc();

##
Persona::dump();


