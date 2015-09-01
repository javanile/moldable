<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
if (function_exists('xdebug_disable')) { xdebug_disable(); }

##
require_once '../../SchemaDB.php';

##
$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile02517';		## database username
$pass = 'java17481';			## database password
$name = 'javanile02517';		## database name	
$pref = 'd1_';					## table prefix

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
	public $id1 = sdbClass::PRIMARY_KEY;
	public $field1 = 5;
	public $field2 = 4;	
}


#Persona::drop('confirm');

##
//Persona::make(array(
//	'field1' => 1,
//	'field2' => 2
//))->store();

##
Persona::desc();

##
#Persona::dump();


