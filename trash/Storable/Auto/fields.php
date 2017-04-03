<?php

//
require_once 'common.php'; 

//
use Javanile\SchemaDB\Storable;

//
class Fields extends Storable
{    
    //    
    static $__define__ = [
        'varchar-size'    => 10,
        'primary-key-size' => 12,
        'string-type'     => 'text',
    ];

    //
    static $class = '';
    static $table = '';
    static $apply = false;

    // key
    public $id = self::KEY;    
}

//
Database::getDefault()->dump();

//
$Fields = new Fields();

//
$Fields->store();