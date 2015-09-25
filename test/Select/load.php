<?php

## 
require_once 'common.php';

##
$id = 1;

##
$name = Person::load($id, 'name');

##
var_dump($name);

