<?php

//
require_once '../common.php';

//
$notations = [

    //
    '<<{"Type":"int(2)"}>>',

    //
    '<<["","value1"]>>',

    // error json only with double quotes
    "<<['','kg','lt']>>",

    '<<["","pz","kg","mt","mq",
        "mc","lt","gal","nr","rt","set","ton"]>>',
    
];

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
	
	<?php foreach ($notations as $notation) {

        $aspects = $parser->getNotationAttributes($notation);
        
        ?>

		<tr>
			<td>
                <pre><?php var_dump(htmlentities($notation)); ?></pre>
            </td>
			<td align="center">
                <?=htmlentities($parser->getNotationValue($notation))?>
            </td>
			<td align="center">
                <?=$parser->getNotationType($notation)?>
            </td>
			<td>
                <pre><?php var_dump($aspects); ?></pre>
            </td>
		</tr>
	<?php } ?>
</table>