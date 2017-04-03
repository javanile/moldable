<?php

// 
require_once 'common.php';

//
$id = 1;

//
$list = Person::query(array(    
    'where' => 'age > 0',
    'field' => 'name',
));

//
var_dump($name);

