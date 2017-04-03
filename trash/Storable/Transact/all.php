<?php

//
require_once 'common.php';

//
Person::getDatabase()->transact();

//
$Persons = Person::update([
    'name',    
    'Address1' => Address::join('name'),
]);

//
if (assert()) {
    Person::getDatabase()->commit();
} else {
    Person::getDatabase()->rollback();
}

//
Person::dump($Persons);
