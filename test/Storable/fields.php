<?php

##
require_once 'common.php'; 

##
use SourceForge\SchemaDB;

##
class Fields extends SchemaDB\Storable {
	
	public $id = self::PRIMARY_KEY;
	
}

##
$Fields = new Fields();

##
$Fields->store();