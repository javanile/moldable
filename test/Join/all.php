<?php

##
require_once 'common.php';

##
$Persons = Person::all([
	'name',	
	'Address1' => Address::join('name'),
]);

##
Person::dump($Persons);
