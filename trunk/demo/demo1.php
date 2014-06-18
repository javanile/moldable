<?php

global $wpdb;

$schema = array(

	"testing" => array(
		"id" => array(			
			"Type" => "text",
			"Null" => "NO",
		),		
	),

);

class People extends schemadb_class {
	
	public $name	= "";
	public $surname	= "";
	public $age		= 0;
	
	public function __construct($name,$surname) {
		$this->name = $name;
		$this->surname = $surname;
	}
	
}

$mario = new People("Mario","Rossi");

$mario->store();



die();



echo '<pre>';

$sql = schemadb($schema,$wpdb->prefix);

function schemadb_query($query,$first=false) {
	global $wpdb;
	
	if ($first) {
		return $wpdb->get_row($query,ARRAY_A);	
	} else {
		return $wpdb->get_results($query,ARRAY_A);			
	}	
}

foreach($sql as $query) {
	echo "query: $query\n";	
	if (!$wpdb->query($query)) {
		echo "error: ".mysql_error()."\n";
	}
}

echo '</pre>';




array(
	"table1" => array(
		"field1" => array()
	)
)
;
