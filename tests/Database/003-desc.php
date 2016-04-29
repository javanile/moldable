<?php

// title
echo '<h1>Database desc with var_dump</h1>';

// require connection parametrs
require_once 'common.php'; 

// Apply schema create or update database tables
$desc = $db->desc();

// var_Dump(...)
echo '<pre>';
var_dump($desc);
echo '</pre>';

// 
$db->benchmark();