<?php
require_once('../schemadb.php');

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 'test3_';				## table prefix

schemadb_debug(true);
schemadb_connect($host,$user,$pass,$name,$pref);

class Junk extends sdbClass {
	
	public $id = MYSQL_PRIMARY_KEY;
	public $bundle = array('yes','not');
	public $naty = false;
	public $parenat = array('Type'=>'varchar(11)','Default'=>12);
	
}

$o = new Junk;

$o->store();