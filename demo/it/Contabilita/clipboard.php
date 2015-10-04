<?php


##
$ok = $db->apply(array(
	
	'Utenti' => array(
		'id' => 0
	)
	
));

##
if ($ok) {
	echo 
	'Il database è stato aggiornato correttamente!';
	
} else {
	echo 
	'Nessuna modifica al database';
	
}



