<?php

//
require_once 'common.php';

//
$results0 = $db->query('People', [

    //
    'age'     => 10,
    
    //
    'order'   => ['name' => 'ASC'],
]);

//
$results1 = $db->query('People', [

    //
    'age'     => 10,

    //
    'order'   => 'name ASC',
]);


//
$db->dump($results);
