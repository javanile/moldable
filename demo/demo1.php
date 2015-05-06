<?php


##
class Person extends sdbClass {
	
	public $id			= MYSQL_PRIMARY_KEY;
	
	public $name		= '';
	public $surname		= '';
	
	public $father		= '<<Person>>';	
	public $mother		= '<<Person>>';	
	
	public $partner		= '<<Person>>';
	public $childrens	= '<<Person*:father>>';	
	
	public $cars		= '<<Car*:owner>>'; 
}

##
$item = Person::build(array(
	'name'		=> '',
	'surname'	=> '',
	'father'	=> array(
		'name'		=> '',
		'surname'	=> '',	
	),
));

##
$item->fill(array(
	'name' => 'Ciao',
));

