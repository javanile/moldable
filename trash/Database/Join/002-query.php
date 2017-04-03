<?php

//
require_once 'common.php';

//
$results = $db->query('People', [
    'age'     => 10,
    'address' => $db->join('Address'),   
]);

//
$db->dump($results);