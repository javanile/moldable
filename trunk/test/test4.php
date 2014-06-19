<?php
require_once('../schemadb.php');

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 'test3_';				## table prefix

schemadb_debug(true);
schemadb_connect($host,$user,$pass,$name,$pref);

class Quarto extends sdbClass {
	
	#static $class = __CLASS__;
	#static $table = __CLASS__;
	
	public $f1 = 10;
			
}

$o = new Quarto();

print Quarto::table() .'<br/>';

