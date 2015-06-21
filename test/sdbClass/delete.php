<?php
ini_set('display_errors',true);
error_reporting(E_ALL);

require_once '../../schemadb.php';

$host = 'm-04.th.seeweb.it';
$name = 'javanile04844';
$user = 'javanile04844';
$pass = 'java07441';
$pref = 'sdb_delete_';			## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

##
class Item extends sdbClass {
	
	public $id = MYSQL_PRIMARY_KEY;
	
	public $name = "";
	
	public $age = 0;
	
}

##
Item::import(array(
	array('name' => 'Francesco', 'age' => 10),
	array('name' => 'Paolo', 'age' => 12),
	array('name' => 'Piero', 'age' => 10),
	array('name' => 'Antonio', 'age' => 13),	
));

##
Item::dump();

##
Item::delete(array('age'=>10));

##
Item::dump();



