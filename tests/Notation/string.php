<?php

//
require_once '../common.php';

//
require_once __BASE__.'/vendor/autoload.php';

//
use Javanile\SchemaDB;

//
$notations = array(    
        
    // String 
    null,
    "",
    "Hello World!",
    SchemaDB\Notations::VARCHAR,
    SchemaDB\Notations::TEXT,
);

?>

<table border=1 cellpadding=4>
    <tr><th>Notation</th><th>Value</th><th>Type</th><th>Column</th></tr>
    
    <?php foreach($notations as $notation) { ?>
        <tr>
            <td><pre><?php var_dump($notation); ?></pre></td>
            <td align="center"><?=SchemaDB\SchemaParser::getNotationValue($notation)?></td>
            <td align="center"><strong><?=SchemaDB\SchemaParser::getNotationType($notation)?></strong></td>
            <td><pre><?=var_dump(SchemaDB\SchemaParser::parseSchemaTableField(null,$notation,null))?></pre></td>
        </tr>
    <?php } ?>
</table>