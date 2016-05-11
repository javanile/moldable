<?php

//
require_once '../common.php';

//
use Javanile\SchemaDB\Database;

//
new Database([
    'host'     => $host,
    'dbname'   => $dbname,
    'username' => $username,
    'password' => $password,
    'prefix'   => $prefix,
]);
