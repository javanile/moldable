<?php
ini_set('display_errors',true);
error_reporting(E_ALL);


require_once '../../schemadb.php';

/*_*/
$type = 'sqlite3';
$host = __DIR__;
$name = '1.sq3';
$user = 'root';
$pass = 'root';
$pref = 'test1_';
/*/
$type = 'mysql';
$host = 'm-04.th.seeweb.it';
$name = 'javanile04844';
$user = 'javanile04844';
$pass = 'java07441';
$pref = 'test1_';
/*_*/

##
schemadb::connect(
	$type,
	$host,
	$name,
	$user,
	$pass,
	$pref
);

##
class People extends sdbClass {
	
	public $pid = MYSQL_PRIMARY_KEY;
	public $name = "";
	public $surname = "";
	public $age = 0;
	 
}

People::schemadb_update();
