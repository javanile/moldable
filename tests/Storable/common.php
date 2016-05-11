<?php

//
require_once '../common.php';

//
use Javanile\SchemaDB\Database;

// connect to db
/* */
$db = new Database([

    //
    'sokect' => 'Pdo',

    //
    'host'     => $host,
    'dbname'   => $dbname,
    'username' => $username,
    'password' => $password,
    'prefix'   => $prefix,

    //
    'adamant' => false,
    'debug'   => true,
]);
/* */
