<?php

## require connection parametrs
require_once 'common.php'; 

## Apply schema create or update database tables
$desc = $db->desc();

##
echo '<pre>';
var_dump($desc);
echo '</pre>';

##
$db->benchmark();