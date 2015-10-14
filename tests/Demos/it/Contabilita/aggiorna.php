<?php

// richiamo la libreria SchemaDB
require_once 
'../../../src/SourceForge/SchemaDB/autoload.php';

// richiamo i parametri di configurazione
require_once 
'config.php';

// utilizza il namespace della libreria
// per poter richiamare le classi
use SourceForge\SchemaDB;

// connette al database mysql da utilizzare
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => $pref,
));

//
$ok = $db->apply(array(
	
	// tabella utenti
	'Utenti' => array(
		'id' => 0,
		'username' => '',
		'password' => '',
		'ts' => '',
		'ip' => 0,
	),
	
	//
	'Clienti' => array(
		'RagioneSociale' => '',
		'Email' => '',
	),
	
	//
	'Fatture' => array(
		'Cliente' => 0,
		'Totale' => 0,		
	), 	
));

//
if ($ok) {
	echo 
	'Il database &egrave; stato aggiornato correttamente!';
	
} else {
	echo 
	'Nessuna modifica al database';
	
}





