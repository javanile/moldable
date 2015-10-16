<?php

//
require_once 'common.php';

// import Persons from array-of-array
$db->insert('People', array(
    'name' => 'Francesco',
    'age' => 10
));

// printout table record before delete
$db->dump('People');
