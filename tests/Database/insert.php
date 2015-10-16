<?php

//
require_once 'common.php';

//
$db->drop('confirm');

//
$db->alter('People', array(
    'name'  => '',
    'age'   => 0,
));

// import one People
$db->insert('People', array(
    'name'   => 'Francesco',
    'ageing' => 10
),array(
    'ageing'=>'age',
));

// printout table record
$db->dump('People');
