<?php


function schemadb_default($p) {
	switch ($p) {
		case "Null":	return SCHEMADB_DEFAULT_NULL;
		case "Type":	return SCHEMADB_DEFAULT_TYPE;
		case "Default":	return SCHEMADB_DEFAULT_DEFAULT;			
	}	
}
