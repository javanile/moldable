<?php

// 
require_once '../common.php';

//
$db->import('User', 'User.csv');

//
$db->dump('User');