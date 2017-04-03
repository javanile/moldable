<?php

//
require_once __DIR__.'/../../common.php';

//
use Javanile\SchemaDB\Database;

//
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

//
$db->drop('Person', 'confirm');

//
$db->apply('Person', [
    'id'       => $db::PRIMARY_KEY,
    'name'     => '',
    'surname'  => "",
    'age'      => 0,
    'address1' => 0,
    'address2' => 0,
]);

//
$db->import('Person', [
    ['name' => 'Frank', 'surname' => 'Joy', 'address1' => 1, 'address2' => 3, 'sex' => 'M'],
    ['name' => 'Dana',  'surname' => 'Ci',  'address1' => 2, 'address2' => 4, 'sex' => 'F'],
]);

die();

//
$db->drop('Address', 'confirm');

//
$db->apply('Address', [
    'id'   => $db::PRIMARY_KEY,
    'name' => '',
    'lat'  => .0,
    'lng'  => .0,
    'city' => '',
]);

//
$db->import('Address', [
    ['name' => 'Route Red',   'lat' => 10.1, 'lng' => 1.2, 'city' => 'Palermo'],
    ['name' => 'Grenn Place', 'lat' => 31.4, 'lng' => 2.6, 'city' => 'Bruxel'],
    ['name' => 'Every Day',   'lat' => 52.3, 'lng' => 8.6, 'city' => 'Cann'],
    ['name' => 'New Moon',    'lat' => 71.2, 'lng' => 2.6, 'city' => 'Lione'],
]);

 