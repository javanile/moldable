<?php
ini_set('display_errors',true);
error_reporting(E_ALL);

require_once('../schemadb.php');

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 't6_';					## table prefix

class A extends sdbClass {
	public $n;
}

for($i=0;$i<100;$i++) {
	$o = new A();
	$o->n = $i*2;
	$o->store();
}

foreach(A::all() as $o) {
		
}