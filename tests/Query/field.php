<?php

##
require_once 'common.php'; 

##
require_once '../../SchemaDB.php';

##
$list = Person::query(array(
	'where' => 'age > 11',
	'order' => 'age ASC',
	'limit' => 2,
));

##
Person::dump($list);


