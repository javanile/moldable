<?php

//
require_once 'common.php'; 

//
$Person = new Person();

// update before store
$Person->store([
	'name' => 'Frank',
]);

