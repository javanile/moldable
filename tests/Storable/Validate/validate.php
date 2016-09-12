<?php

// require connection parametrs
require_once '../common.php'; 

// retrive main class to enstablish connection
use Javanile\SchemaDB\Storable;

//
class Validable extends Storable
{        
    //
    public $id = self::PRIMARY_KEY;
    
    //
    public $name = self::VARCHAR;    
    
    //
    public $ts = self::DATETIME;
    
    //
    public function decode()
    {
        $this->ts = date('d/m/Y', $this->ts);
    }    
}

// 
$decoded = Validable::decode();

//
echo '<pre>';
var_dump($decoded);
echo '</pre>';

