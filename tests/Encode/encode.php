<?php

// require connection parametrs
require_once '../common.php'; 

// require library
require_once '../../src/SourceForge/SchemaDB/autoload.php';

// retrive main class to enstablish connection
use SourceForge\SchemaDB;

//
class TestModel extends SchemaDB\Storable {	
	
	//
	public $pid = self::PRIMARY_KEY;
	
	//
	public $name = self::VARCHAR;	
	
	//
	public $ts = self::DATETIME;
	
	//
	public static function encode_ts($date) {
	
		//
		return (int) $date;
	}	
}

// 
$encoded = TestModel::encode(array(
	'ts' => '1/1/1981', 
));

//
echo '<pre>';
var_dump($encoded);
echo '</pre>';

