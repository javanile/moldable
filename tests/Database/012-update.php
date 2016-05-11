<?php

//
require_once 'common.php';

//
#$db->drop('confirm');

//
$db->alter('People', array(
    'id'    => $db::PRIMARY_KEY,
    'name'  => '',
    'age'   => 0,
    'where' => 'age > :america',
    'america' => $america,
));

// import one People
$db->update('People', 1, array(
    'age' => 13
));

// printout table record
$db->dump('People');
