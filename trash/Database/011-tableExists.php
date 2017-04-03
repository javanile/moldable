<?php

//
echo '<h1>Test if table exists</h1>';

//
require_once '../common.php';

// require library
require_once '../../src/Javanile/SchemaDB/autoload.php';

//
use Javanile\SchemaDB;

//
$db = new SchemaDB\Database(array(
    'host' => $host,
    'user' => $user,
    'pass' => $pass,
    'name' => $name,
    'pref' => 't100_',
));

// drop tables
#$db->drop('confirm');

// create table
#$db->applyTable('Project', array('id' => 0));

//
if ($db->tableExists('Project')) {
    echo 'Table already exists!';
} else {
    echo 'No table found.';
}
