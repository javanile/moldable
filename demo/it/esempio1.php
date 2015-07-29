<?php

## richiamo la libreria tutto in uno file
## scaricalo qui: http://sourceforge.net/p/schemadb/code/HEAD/tree/schemadb.php?format=raw 
require_once '../../schemadb.php';

## connette al database mysql che vorrete utilizzare
schemadb::connect(
    'sql2.freemysqlhosting.net', ## host del database
    'sql285341', ## utente del database
    'uP3*uQ4*', ## password del database
    'sql285341', ## nome del database
    'es1_' ## prefisso delle tabelle del database
);

##
class Persona extends sdbClass {
    public $nome = "";
    public $cognome = "";
    public $eta = 0;
	public $creatoil = MYSQL_DATETIME;
}

## crea un oggetto persona 
$persona = Persona::build(array(
    'nome' => 'Mario',
    'cognome' => 'Rossi',
    'eta' => 40,
	'creatoil' => MYSQL_NOW(),
));

## salva l'oggetto persona nel database
$persona->store();

## mostra il contenuto di tutta la tabella
Persona::dump();