<?php

##
require_once 'common.php';

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
