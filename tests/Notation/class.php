<?php

//
require_once '../../src/SourceForge/SchemaDB/autoload.php';

//
use SourceForge\SchemaDB;

//
$notations = array(	
	
	// Class
	'<<Class>>',
	'<<Class*>>',
);

?>

<table border=1 cellpadding=4>
	<tr><th>Notation</th><th>Value</th><th>Type</th><th>Column</th></tr>
	
	<?php foreach($notations as $notation) { ?>
		<tr>
			<td><?php var_dump($notation); ?></td>
			<td align="center"><?=SchemaDB\Parser::getNotaionValue($notation)?></td>
			<td align="center"><strong><?=SchemaDB\Parser::getNotationType($notation)?></strong></td>
			<td><pre><?=var_dump(SchemaDB\Parser::parseSchemaTableField(null,$notation))?></pre></td>
		</tr>
	<?php } ?>
</table>