<?php

// 
require_once 'common.php';

//
use SourceForge\SchemaDB;

//
SchemaDB\Database::getDefault()->drop('confirm');

//
class Invoice extends SchemaDB\Storable {
		
	//
	public $code = '';
	
	//
	public $products = '<<Product*>>';
}

//
class Product extends SchemaDB\Storable {

	//
	public $id = self::PRIMARY_KEY;
	
	//
	public $name = '';
	
	//
	public $price = .0;	
}

//
class InvoiceProduct extends SchemaDB\Storable {

	//
	public $id = self::PRIMARY_KEY;
	
	//
	public $description = '';
	
	//
	public $price = .0;	

	//
	public $quantity = 0;	
}

//
$Invoice0 = new Invoice(array(
	'code' => 'FAT1',
	'products' => array(
		array('name' => 'Product No 1','price' => 1.3,),
		array('name' => 'Product No 2','price' => 1.6,),
		array('name' => 'Product No 3','price' => 2.1,),
	),
));

//
$Invoice0->store();

//
Invoice::dump();

//
Product::dump();

//
$Invoice1 = Invoice::load('FAT1');

//
echo '<pre>';
var_Dump($Invoice1);
echo '</pre>';