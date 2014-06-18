<?php


function schemadb_create_table($n,$s) {
	
	$e = array();
	
	foreach($s as $f=>$d) {
		if (is_numeric($f)&&is_string($d)) {
			$f = $d;
			$d = array();
		}		
		$e[] = $f.' '.schemadb_column_definition($d);
	}	
	
	$e = implode(',',$e);
	
	$q = "CREATE TABLE $n ({$e})";
	
	return $q;
}
