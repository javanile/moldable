<?php

## 
require_once 'common.php';

##
use SourceForge\SchemaDB;

##
class Invoice extends SchemaDB\Storable {
	
	##
	public $product = '<<Product>>';
}

echo '<pre>';

var_dump(Invoice::getSchemaFields());

var_dump(Invoice::getSchemaFieldsWithValues());

echo '</pre>';