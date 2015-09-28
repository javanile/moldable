<?php

##
require_once 'common.php';

##
$id = 1;



##
$Person = Person::query(array(
	'field' => array(
		'name',	
		'a1_*' => Address::join(),
		'a2_*' => Address::join(),		
	),
));

##
var_Dump($Person);
