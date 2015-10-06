<?php

##
require_once 'common.php'; 

##
use SourceForge\SchemaDB;

##
class Fields extends SchemaDB\Storable {
	
	##	
	static $__Define__ = array(
		'DefaultVarcharSize'	=> 10,
		'DefaultPrimaryKeySize' => 12,
	);   
	
	## key
	public $id = self::PRIMARY_KEY;
	
	## string
	public $string0 = '';
	public $string1 = 'sample string';
	public $string2 = self::VARCHAR_32;
	public $string3 = self::VARCHAR_64;
	public $string4 = self::VARCHAR_128;
	public $string5 = self::VARCHAR_255;
	public $string6 = self::TEXT;		
}

##
$Fields = new Fields();

##
$Fields->store();