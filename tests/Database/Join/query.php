<?php

//
require_once 'common.php';

//
$Persons = Person::query([
	'field' => [
		'name',	
		'a1_*' => Address::join(),
		'a2_*' => Address::join(),		
	],
]);

//
var_Dump($Persons);

