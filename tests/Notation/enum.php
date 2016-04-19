<?php

//
require_once '../common.php';

//
require_once __BASE__.'/vendor/autoload.php';

//
use Javanile\SchemaDB;

//
$notations = array(    
        
    // Enum and set
    array(0,1,2),
    array('sa','admin','public'),
    array(null,'apple','banana','cocco'),
    array(0, 'asd'=>'asd',),
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
            <td><pre><?=var_dump(SchemaDB\SchemaParser::getNotationAttributes($notation))?></pre></td>
        </tr>
    <?php } ?>
</table>