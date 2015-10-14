<?php

// richiamo la libreria SchemaDB
require_once 
'../../../src/SourceForge/SchemaDB/autoload.php';

// richiamo i parametri di configurazione
require_once 'config.php';

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
$db->drop('confirm');