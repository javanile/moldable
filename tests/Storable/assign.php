<?php


##
require_once 'common.php'; 

##
use SourceForge\SchemaDB\SchemaDB;

##
use SourceForge\SchemaDB\Storable;

##
new SchemaDB(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => $pref,
));

##
$Person = new Person();

## 
$Person->store(array(
	'name' => 'Frank',
));

