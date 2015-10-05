<?php

##
require_once 'common.php';

##
Person::dump();

##
Address::dump();

##
$Person0 = Person::load(1);

##
$Person1 = Person::load(1, array(
	'address1', 
	'address2',
));

##
echo '<pre>';
var_Dump($Person0);
var_Dump($Person1);
