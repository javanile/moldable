<?php

//
echo '<h1>Print-out database schema</h1>';

//
require_once 'common.php';

//
$db->dump();

//
$db->benchmark();