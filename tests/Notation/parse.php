<?php

//
require_once '../../src/SourceForge/SchemaDB/autoload.php';

//
use SourceForge\SchemaDB;

//
$schema = array(	
	
	'My_Table' => array(
		
		'My_Field' => 1,
		
	)
	
);

//
echo '<pre>';
var_dump($schema);
echo '</pre>';

//
SchemaDB\Parser::parseSchemaDB($schema);

//
echo '<pre>';
var_dump($schema);
echo '</pre>';
