<?php

function schemadb_pseudotype_parse($v) {
	
	$t = gettype($v);
			
	switch ($t) {
		case 'string':
			if (preg_match('/^<<[_a-zA-Z][_a-zA-Z0-9]*>>$/i',$v,$d)) {
				return 'class';
			} else if (preg_match('/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]/',$v)) {				
				return 'datetime';				
			} else if (preg_match('/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]/',$v)) {
				return 'date';				
			} else if ($v == MYSQL_PRIMARY_KEY) {
				return 'primary_key';				
			}
			return 'string';
			
		case 'integer':
			return 'integer';
								
		case 'array':
			return 'array';		
	}	
}

function schemadb_pseudotype_value($v) {
	
	$t = schemadb_pseudotype_parse($v);
	
	switch($t) {
		case 'integer'		: return (int) $v;		
		case 'primary_key'	: return NULL;
		case 'string'		: return (string) $v;
		case 'class'		: return NULL;
		case 'array'		: return NULL;	
		case 'date'			: return NULL;
	}		
	
	trigger_error("No PSEUDOTYPE value for '{$t}' => '{$v}'",E_USER_ERROR);		
}
