<?php

/*\
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 *
 *
 *
 */
class Debug 
{
	
	
	public static function var_dump($var) {
		echo '<pre style="padding:4px 6px 2px 6px; background:#eee;border:1px solid #ccc; margin:0 0 1px 0;">';			
		var_dump($var);
		echo '</pre>';
	} 
	
	
	
}