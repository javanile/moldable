<?php
require_once('../schemadb.php');

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 'test2_';				## table prefix

schemadb_debug(true);
schemadb_connect($host,$user,$pass,$name,$pref);

class Junk extends sdbClass {
	
	public $id = MYSQL_PRIMARY_KEY;
	public $bundle;
	public $naty;
	public $parent=0;
	
	public function getName() {
		
		
	}
	
}

$p = 0;
for ($i = 0; $i<10; $i++ ) {
	$j = new Junk();
	$j->parent = $p;
	$p = $j->store();
}

$j->bundle = 10;

$j->store();
