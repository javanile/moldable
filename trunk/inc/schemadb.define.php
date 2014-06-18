<?php

// build mysql column define rule 
function schemadb_define($v) {
	
	$t = schemadb_pseudotype_parse($v);
	
	switch ($t) {
		case 'date'			: return schemadb_define_rule('date',$v);
		case 'datetime'		: return schemadb_define_rule('datetime',$v);
		case 'primary_key'	: return schemadb_define_rule('int(10)','','NO','PRI','auto_increment');
		case 'string'		: return schemadb_define_rule('varchar(255)');			
		case 'integer'		: return schemadb_define_rule('int(10)',(int)$v,'NO');			
		case 'array':
			foreach($v as &$i) {
				$i = "'".$i."'";
			}
			$t = 'enum('.implode(',',$v).')';
			return schemadb_define_rule($t,'','NO');									
	}
	
}
	
//
function schemadb_define_rule($t='int(10)',$d='',$n='',$k='',$e='') {
	return array(
		'Type'		=> $t,	
		'Default'	=> $d,
		'Null'		=> $n,
		'Key'		=> $k,
		'Extra'		=> $e,
	);		
}