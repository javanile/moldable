<?php

//
require_once 'common.php';

//
use Javanile\SchemaDB\Storable;

//
$db->setPrefix('o2m_');

//
$db->drop('*', 'confirm');

//
class Band extends Storable {
    public $name = '';
    public $songs = '<<Song*>>';
}

//
class Song extends Storable {
    public $title = '';
}

//
$newBand = new Band([
    'name' => 'Beatles',
    'songs' => [
        'Let it Beee',
        'Yellow mom',
    ],
]);

//
echo '<pre>';
var_dump($newBand);
echo '</pre>';

//
$id = $newBand->store();

//
$oldBand = Band::load($id);

//
echo '<pre>';
var_dump($oldBand);
echo '</pre>';
