<?php

//
error_reporting(E_ALL);
ini_set('display_errors', 1);

//
require_once __DIR__.'/../../../../autoload.php';

//
$notations = array(    
        
    \Javanile\SchemaDB\Notations::INT_20,
);

//
$parser = new Javanile\SchemaDB\Parser\Mysql();

?>

<table border=1 cellpadding=4>

    <tr>
        <th>Notation</th>
        <th>Value</th>
        <th>Type</th>
        <th>Column</th>
    </tr>
    
    <?php foreach($notations as $notation) { ?>
        <tr>
            <td>
                <?php var_dump($notation); ?>
            </td>
            <td align="center">
                <?=$parser->getNotationValue($notation)?>
            </td>
            <td align="center">
                <strong><?=$parser->getNotationType($notation)?></strong>
            </td>
            <td>
                <pre><?=var_dump($parser->getNotationAttributes($notation))?></pre>
            </td>
        </tr>
    <?php } ?>
</table>