<?php

##
require_once 'common.php';

##
Person::dump();

##
Address::dump();

##
$Person = Person::load(1, array(
	'address1'
));

##
echo '<pre>';
var_Dump($Person);
