<?php

##
require_once 'common.php';

##
$id = 1;

##
$Person = Person::load($id,array(
	'name',
	'surname',
	'address' => 'Address.name',
));

