<?php

//
require_once __DIR__.'/../common.php';

//
use Javanile\SchemaDB\Database;

//
$db = new Database([
    'host'     => $host,
    'dbname'   => $dbname,
    'username' => $username,
    'password' => $password,
    'prefix'   => $prefix,
    'auto'     => false,     //
]);

//
$db->setDebug(true);

