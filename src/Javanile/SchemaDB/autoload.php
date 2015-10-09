<?php

if (!function_exists("SchemaDB_autoload")) {
	function SchemaDB_autoload($className) {
		
		$classPath = explode('\\',$className);

		if (count($classPath) < 3 || $classPath[1] != 'SchemaDB') {
			return;
		}

		$classFile = __DIR__.'/'.$classPath[2].'.php';
			
		if (!include_once $classFile) {
			return;
		}	
	}
}

spl_autoload_register('SchemaDB_autoload');

