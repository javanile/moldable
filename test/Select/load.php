<?php

## 
require_once 'common.php';

##
$id = 1;

##
$name = Person::load($id, 'surname');

##
var_dump($name);

