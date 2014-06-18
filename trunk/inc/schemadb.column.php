<?php



function schemadb_column_definition($d) {
	#
	/*
	column_definition:
    data_type [NOT NULL | NULL] [DEFAULT default_value]
      [AUTO_INCREMENT] [UNIQUE [KEY] | [PRIMARY] KEY]
      [COMMENT 'string']
      [COLUMN_FORMAT {FIXED|DYNAMIC|DEFAULT}]
      [STORAGE {DISK|MEMORY|DEFAULT}]
      [reference_definition]	
	 */
	$t = isset($d["Type"]) ? $d["Type"] : schemadb_default("Type");
	$u = isset($d["Null"]) && ($d["Null"]=="NO" || !$d["Null"]) ? 'NOT NULL' : 'NULL';
	$l = isset($d["Default"]) && $d["Default"] ? "DEFAULT '$d[Default]'" : '';
	$e = isset($d["Extra"]) ? $d["Extra"] : '';
	$p = isset($d["Key"])&&$d["Key"]=="PRI" ? 'PRIMARY KEY' : '';
	$f = isset($d["First"])&&$d["First"] ? 'FIRST' : '';
	$b = isset($d["Before"])&&$d["Before"] ? 'AFTER '.$d["Before"] : '';
	$q = "{$t} {$u} {$l} {$e} {$p} {$f} {$b}";
	return $q;
}


