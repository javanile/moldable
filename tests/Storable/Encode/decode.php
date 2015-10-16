<?php

// require connection parametrs
require_once '../common.php'; 

// require library
require_once '../../src/SourceForge/SchemaDB/autoload.php';

// retrive main class to enstablish connection
use SourceForge\SchemaDB;

//
class ModelDecoder extends SchemaDB\Storable {	
	
	//
	public $id = self::PRIMARY_KEY;

	//
	public $ts = self::DATETIME;

	//
	public $name = self::VARCHAR;		
	
	//
	public function decode_($value) {
	
		//
		return $value;
	}
	
	//
	public function decode_ts($ts) {
	
		//
		return date('d/m/Y', $ts);
	}
}

// 
$decoded = ModelDecoder::decode(array(
	'ts' => time(), 
));

//
echo '<pre>';
var_dump($decoded);
echo '</pre>';
