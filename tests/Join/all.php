<?php

##
require_once 'common.php';

##
$Persons = Person::all(array(
	'name',	
	'address' => Address::join('name','address'),
));

##
Person::dump($Persons);
