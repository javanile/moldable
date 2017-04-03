<?php

//
require_once '../../src/Javanile/SchemaDB/autoload.php';

//
use Javanile\SchemaDB;

//
$notations = array(    
        
    // Enum and set
    array(0,1,2),
    array('sa','admin','public'),
    array(null,'apple','banana','cocco'),
);
?>

<table border=1 cellpadding=4>
    <tr><th>Notation</th><th>Value</th><th>Type</th><th>Column</th></tr>
    
    <?php foreach($notations as $n) { 
        $notation = $n;
        ?>
        <tr>
            <td><?php var_dump($notation); ?></td>
            <td align="center"><?=SchemaDB\SchemaParser::getNotationValue($notation)?></td>
            <td align="center"><strong><?=SchemaDB\SchemaParser::getNotationType($notation)?></strong></td>
            <td><pre><?=var_dump(SchemaDB\SchemaParser::parseSchemaTableField(null,$notation))?></pre></td>
        </tr>
    <?php } ?>
</table>