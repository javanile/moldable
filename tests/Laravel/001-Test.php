<?php

echo '<h2>001-Test.php</h2>';

use Javanile\SchemaDB\Storable;
use Javanile\SchemaDB\Readable;

class People extends Storable
{
    //
    static $config = [
        'Table' => 'Ciccio',
        'Adamant' => true,
        'DefaultStringType' => self::TEXT
    ];

    //
    static $adamant = true;

    //
    static $__adamant__ = true;

    //
    static $table = "ciao";

    //
    public $name = "";
}


$Frank = new People(['name' => 'Frank']);

echo '<pre>';
var_dump(People::$__config__);

