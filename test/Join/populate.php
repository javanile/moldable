<?php

##
require_once 'common.php';

##
Person::drop('confirm');

##
Person::import(array(
	array(
		'name' => 'Mario', 
		'surname' => 'Rossi',
		'age' => 21,
		'address' => 1
	),
	array(
		'name' => 'Franco', 
		'surname' => 'Verde',
		'age' => 22,
		'address' => 2
	),	
));

##
Person::dump();

##
Address::drop('confirm');

##
Address::import(array(
	array(
		'name' => 'Via Palestro',
		'latitude' => 1000,
		'longitude' => 2000,
	),
	array(
		'name' => 'Via Milano',
		'latitude' => 1400,
		'longitude' => 2100,
	),
));

##
Address::dump();