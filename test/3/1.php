<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once '../../schemadb.php';

$host = 'localhost';	## database host 
$user = 'root';			## database username
$pass = 'root';			## database password
$name = 'db_schemadb';	## database name	
$pref = 't31_';			## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

class People extends sdbClass {	
	public $pid = MYSQL_PRIMARY_KEY;
	public $name = "";
	public $surname = "";
	public $age = 0;
	public $address = '<<Address>>';
}

class Address extends sdbClass {
	public $aid = MYSQL_PRIMARY_KEY; 
	public $address = "";
	public $zip = 0;
}

$people0 = People::insert([
	'name' => 'Mario',
	'surname' => 'Rossi',
	'address' => [
		'address' => 'via milano',
		'zip' => 91022
	]
]);

$people1 = People::load($people0->pid);

var_dump($people1);