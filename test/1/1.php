<?php
ini_set('display_errors',true);
error_reporting(E_ALL);
xdebug_disable();

require_once('../../schemadb.php');

$values = array('%|schema:{Type:"int(2)"}|%','%|class:|%','%|key:primary_key|%');

echo '<table border=1 cellpadding=4><tr><th>Value</th><th>Type</th><th>Column</th></tr>';
foreach($values as $value) {
	echo '<tr><td>';
	var_dump($value); 
	echo '</td><td><strong>';
	echo schemadb::get_type($value);
	echo '</strong></td><td><pre>';
	var_dump(schemadb::schema_parse_table_column($value));
	echo '</pre></td></tr>';
}
echo '</table>';