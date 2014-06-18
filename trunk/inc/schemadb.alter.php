<?php


function schemadb_alter_table_add($n,$f,$d) {
	$c = schemadb_column_definition($d);
	$q = "ALTER TABLE $n ADD  {$f} {$c}";	
	return $q; 	
}

function schemadb_alter_table_change($n,$f,$d) {
	$c = schemadb_column_definition($d);
	$q = "ALTER TABLE $n CHANGE {$f} {$f} {$c}";	
	return $q; 
}