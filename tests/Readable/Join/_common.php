<?php

//
use Javanile\SchemaDB\Database;

//
if (!Database::hasDefault())
{
    //
    require_once '../common.php'; 

    //
    new Database([
        'host'     => $host,
        'dbname'   => $name,
        'username' => $user,
        'password' => $pass,
        'prefix'   => 'Test_Join_',
    ]);
}

//
Database::getDefault()->setDebug(true);
