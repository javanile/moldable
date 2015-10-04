<?php

## 
require_once 'common.php';

##
use SourceForge\SchemaDB\Storable;

##
class Invoice extends Storable {
	
	##
	public $product = '<<Product>>';
}

##
class Product extends Storable {

	##
	public $name = '';
	
	##
	public $price = .0;	
}

##
$Invoice = new Invoice(array(
	'product' => array(
		'name' => 'Product No 1',
		'price' => 1.3,
	),
));

##
$Invoice->store();

##
Invoice::dump();

##
Product::dump();
