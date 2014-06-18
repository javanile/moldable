<?php



function schemadb_action($method,$sql=NULL) {
	
	global $ezdb;
	
	switch($method) {
		case "prefix":	return $ezdb->prefix;
		case "last_id":	return $ezdb->insert_id;
		
		case "query":	$return = $ezdb->query($sql); break;
		case "row":		$return = $wpdb->get_row($sql,ARRAY_A); break;
		case "results":	$return = $wpdb->get_results($sql,ARRAY_A); break;
		default:		$return = $wpdb->get_results($sql,ARRAY_A); break;
	}
	$error = mysql_error();
	if ($error) {
		die('Error: '.$error.'<br/>Query: '.$sql);
	}
	return $return;
}