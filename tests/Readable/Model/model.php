<?php

// 
require_once 'common.php';

//
use Javanile\Liberty\Storable;

//
class Product extends Storable {

    //
    public $name = "";

    //
    public $price = .0;
}

//
class Invoice extends Storable {
    
    //
    public $product = '<<Product>>';
}

