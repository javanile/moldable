<?php

//
require_once '../../common.php';

//
require_once __BASE__.'/vendor/autoload.php';

//
use Javanile\SchemaDB;

//
$db = new SchemaDB\Database(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => 't103_',
));

//
$db->setDebug(true);