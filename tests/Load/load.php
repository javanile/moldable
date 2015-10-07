<?php

## 
require_once 'common.php';

##
use SourceForge\SchemaDB;

##
SchemaDB\Database::getDefault()->drop('confirm');

##
class Invoice extends SchemaDB\Storable {
		
	##
	public $code = '';
	
	##
	public $product = '<<Product>>';
}

##
class Product extends SchemaDB\Storable {

	##
	public $id = self::PRIMARY_KEY;
	
	##
	public $name = '';
	
	##
	public $price = .0;	
}

##
$Invoice0 = new Invoice(array(
	'code' => 'FAT1',
	'product' => array(
		'name' => 'Product No 1',
		'price' => 1.3,
	),
));

##
$Invoice0->store();

##
Invoice::dump();

##
Product::dump();

##
$Invoice1 = Invoice::load('FAT1');

##
echo '<pre>';
var_Dump($Invoice1);
echo '</pre>';