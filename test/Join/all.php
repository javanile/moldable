<?php

##
require_once 'common.php';

##
$Persons = Person::all(array(
	'name',	
	'full_name' => "CONCAT(Person.name,' ',surname)",
	'address1' => Address::join('name','address'),
));

##
Person::dump($Persons);
