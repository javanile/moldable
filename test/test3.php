<?php
ini_set('display_errors',true);
error_reporting(E_ALL);

require_once('../schemadb.php');

$host = 'm-04.th.seeweb.it';	## database host 
$user = 'javanile93298';		## database username
$pass = 'java90898';			## database password
$name = 'javanile93298';		## database name	
$pref = 't3_';					## table prefix

schemadb::connect($host,$user,$pass,$name,$pref);

class Junk extends sdbClass {
	
	static $table	= 'junka';
	
	public $id		= MYSQL_PRIMARY_KEY;
	public $type	= '<<JunkType>>';
	public $naty	= false;
	public $parat	= array(
		'Type' => 'varchar(11)',
		'Default' => 12
	);
			
}

class Document extends sdbClass {
	
	static $table	= 'junka2';
	
	public $id		= MYSQL_PRIMARY_KEY;
	public $type	= '<<JunkType>>';
	public $naty	= false;
	public $parat	= array(
		'Type' => 'varchar(11)',
		'Default' => 12
	);
			
}



$d = new Document;

$d->store();

$o = new Junk;

$id = $o->store();


schemadb::dump();