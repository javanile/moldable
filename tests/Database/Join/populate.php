<?php

//
require_once 'common.php';

//
$db->import('Person', array(
    array(
        'name' => 'Mario',
        'surname' => 'Rossi',
        'age' => 21,
        'address1' => 1,
        'address2' => 2,
    ),
    array(
        'name' => 'Franco',
        'surname' => 'Verde',
        'age' => 22,
        'address1' => 2,
        'address2' => 1,
    ),
));

//
$db->dump('Person');

//
$db->import('Address', array(
    array(
        'name' => 'Via Palestro',
        'latitude' => 1000,
        'longitude' => 2000,
        'city'    => 'Palermo',
    ),
    array(
        'name' => 'Via Milano',
        'latitude' => 1400,
        'longitude' => 2100,
        'city'    => 'Catania',
    ),
));

//
$db->dump('Address');
