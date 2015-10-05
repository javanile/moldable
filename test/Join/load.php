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
	'address_name_1' => Address::join('address1'),		
	'address_name_2' => Address::join('address2'),  	
	'address_name_3' => Address::join('address1'),	
	'address_name_4' => Address::join('address2'),  
	'Address2.city',
));

echo '<pre>';
var_Dump($Person1);
echo '</pre>';
