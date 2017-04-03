<?php

//
require_once '../common.php'; 

//
require_once '../../SchemaDB.php';

//
use SourceForge\SchemaDB\SchemaDB;

//
use SourceForge\SchemaDB\Storable;

//
new SchemaDB(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => 't101_',
));

//
class Person extends Storable {
    
    //
    static $class = 'Person_2';
    
    //
    static $table = 'Ciao';
    
    //
    public $id = self::PRIMARY_KEY;     
    public $name = "";
    public $surname = "";
    public $age = 0;
    public $address = 0;
    
}

//
echo '<pre>';
