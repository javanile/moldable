<?php


function schemadb_desc($t) {
	$i = schemadb_action("results","DESC {$t}");
	$a = array();		
	$n = 0;
	$b = false;
	foreach($i as $j) {		
		$j["Before"] = $b;		
		$j["First"]	= $n == 0;
		$a[$j["Field"]] = $j;					
		$b = $j["Field"];
		$n++;
	}
	return $a;
}
