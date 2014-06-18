<?php


function schemadb_sanitize_rule($f,$d,$b) {		
	$d["Field"] = $f;
	$d["Type"]	= isset($d["Type"]) ? $d["Type"] : schemadb_default("Type");
	$d["Null"]	= isset($d["Null"]) ? ($d["Null"]&&$d["Null"]!='NO' ? 'YES' : 'NO') : 'YES';	
	$d["Before"] = $b;
	$d["First"]	 = !$b; 
	return $d;	
}