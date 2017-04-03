<?php

//
error_reporting(E_ALL);
ini_set('disply_errors', 1);


//
require_once '../common.php'; 

//
require_once '../../src/Javanile/SchemaDB/autoload.php';

//
use Javanile\SchemaDB;

//
new SchemaDB\Database(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => 't105_',
));
