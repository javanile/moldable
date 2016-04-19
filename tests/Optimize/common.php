<?php

//
require_once '../common.php'; 

//
require_once '../../src/SourceForge/SchemaDB/autoload.php';

//
use SourceForge\SchemaDB;

//
new SchemaDB\Database(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => 't103_',
));

//
class Person extends SchemaDB\Storable {
    
    public $id = self::PRIMARY_KEY;
    public $name = "";
    public $age = 0;
    
}