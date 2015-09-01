<?php

##
error_reporting(E_ALL);
ini_set('display_errors',true);

##
require_once '../../SchemaDB.php';

##
use SourceForge\SchemaDB\Storable;

##
$values = array(
	Storable::DATE,
);

##
echo '<table border=1 cellpadding=4><tr><th>Notation</th><th>Value</th><th>Type</th><th>Column</th></tr>';

##
foreach($values as $value) {
	echo '<tr><td>';
	var_dump($value); 
	echo '</td><td>';
	echo SourceForge\SchemaDB\Parse::get_value($value);
	echo '</td><td><strong>';
	echo SourceForge\SchemaDB\Parse::get_type($value);
	echo '</strong></td><td><pre>';
	var_dump(SourceForge\SchemaDB\Parse::schema_parse_table_column($value));
	echo '</pre></td></tr>';
}

##
echo '</table>';