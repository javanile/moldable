<?php

if (!function_exists("SchemaDB_autoload")) {
function SchemaDB_autoload($className)
{
	$classPath = explode('\\',$className);
	
	if (count($classPath) < 3 || $classPath[1] != 'SchemaDB') {
		return;
	}
	
	
	if (!@include_once __DIR__.'/'.$classPath[2].'.php') {
		
		echo '<pre>';
		debug_print_backtrace();				
		echo '</pre>';
	}	
}
}

spl_autoload_register('SchemaDB_autoload');

