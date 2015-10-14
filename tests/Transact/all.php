<?php

//
require_once 'common.php';

//
Person::getSchemaDB()->transact();

//
$Persons = Person::update([
	'name',	
	'Address1' => Address::join('name'),
]);

//
if (assert()) {
	Person::getSchemaDB()->commit();	
} else {
	Person::getSchemaDB()->rollback();
}


//
Person::dump($Persons);
