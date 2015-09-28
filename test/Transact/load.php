<?php

##
require_once 'common.php';

##
$id = 1;

##
$Person = Person::load($id,array(
	'name',	
	'addres1_*' => Address::join('address1'),
	'addres2_*' => Address::join('address2'),
));

##
var_Dump($Person);
