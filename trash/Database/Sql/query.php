<?php

// setting debug mode
error_reporting(E_ALL);
ini_set('display_errors',true);

// require database config parameters
require_once 'common.php'; 

// call library autoloader
require_once '../../src/SourceForge/SchemaDB/autoload.php';

//
use SourceForge\SchemaDB;

// create db schema connection
$db = new SchemaDB\Source(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => $pref,
));

//
$db->execute("UPDATE {$pref}Person SET name='Ciao' WHERE id=1");


