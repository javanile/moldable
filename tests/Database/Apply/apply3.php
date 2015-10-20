<?php

// require connection parametrs
require_once 'common.php'; 

// drop all database tables
#$db->drop('confirm');

//
$db->apply('Nome della tabella', 'nome del campo', 0);

//
$db->dump();

// 
$db->benchmark();