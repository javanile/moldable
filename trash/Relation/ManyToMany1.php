<?php

//
require_once 'common.php';

//
use Javanile\SchemaDB\Storable;

//
$db->setPrefix('m2m_');

//
$db->drop('*', 'confirm');

//
class Band extends Storable {
    public $name = '';
    public $covers = '<<Song**>>';
}

//
class Song extends Storable {
    public $title = '';
}

//
$newBand1 = new Band([
    'name' => 'Beatles',
    'covers' => [
        'Let it Beee',
        'Yellow mom',
    ],
]);

//
$newBand2 = new Band([
    'name' => 'Led Zeppa',
    'covers' => [
        'Let it Beee',
        'Strait to time',
    ],
]);

//
echo '<pre>';
var_dump($newBand1, $newBand2);
echo '</pre>';

//
$id1 = $newBand1->store();

//
$oldBand1 = Band::load($id1);

//
$id2 = $newBand2->store();

//
$oldBand2 = Band::load($id2);

//
echo '<pre>';
var_dump($oldBand1, $oldBand2);
echo '</pre>';

//
$db->dump('*');