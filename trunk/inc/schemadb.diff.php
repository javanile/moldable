<?php

// generate query to align db
function schemadb_diff($s) {
	
	$p = schemadb_action('prefix');	
	$o = array();
	
	foreach($s as $t=>$d) {
		$q = schemadb_table_diff($p.$t,$d);		
		if (count($q)>0) {
			$o = array_merge($o,$q);
		}
	}
	
	return $o;	
}

// generate query to align table
function schemadb_table_diff($t,$s) {	
	
	$o = array();
		
	// test if table exists
	$e = schemadb_action("row","SHOW TABLES LIKE '{$t}'");
		
	if ($e) {
		$a = schemadb_desc($t);
		$b = false;
		
		// test field definition
		foreach($s as $f=>$d) {			
			
			if (is_numeric($f)&&is_string($d)) {
				$f = $d;
				$d = array();
			}
			
			//echo "a: $t.$f\n<br/>";								
			$d = schemadb_sanitize_rule($f,$d,$b);
			
			if (isset($a[$f])) {
				$u = false;			
				foreach($a[$f] as $k=>$v) {
					$x = isset($d[$k]); 
					$h = $x ? $d[$k] : schemadb_default($k);
					$d[$k] = $h;
					
					if ($h!=$v) {
						$u = true;
					}
					
					#echo "a: $t.$f.$k($v) = [$x] ($h) [$u]\n<br/>";								
				}
				if ($u) {
					$o[] = schemadb_alter_table_change($t,$f,$d);
				}
			} else {
				$o[] = schemadb_alter_table_add($t,$f,$d);
			}
			$b = $f;
		}

	} else {
		$o[] = schemadb_create_table($t,$s);		
	}		
	
	return $o;
}

