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
echo '<pre>';
var_Dump($Person0);
echo '</pre>';

##
$Person1 = Person::load(1, array(
	'address' => Address::join('address1'),  	
));

echo '<pre>';
var_Dump($Person1);
echo '</pre>';
