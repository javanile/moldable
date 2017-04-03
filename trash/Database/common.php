<?php

// include lib and config
require_once __DIR__.'/../common.php';

// class name of database
use Javanile\SchemaDB\Database;

// create database connection
$db = new Database([
    'host'     => $host,
    'dbname'   => $dbname,
    'username' => $username,
    'password' => $password,
    'prefix'   => $prefix,
    'debug'    => true,
]);

