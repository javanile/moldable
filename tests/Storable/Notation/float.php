<?php

//
require_once 'common.php';

//
use Javanile\SchemaDB;

//
class MyFloat extends SchemaDB\Storable
{
    public $float_1 = 0.;
    public $float_2 = .0;
    public $float_3 = self::FLOAT;
    public $float_4 = self::DOUBLE;
}

//
//MyDatetime::drop('confirm');

//
$item = new MyFloat;

//
$item->store();

//
MyFloat::dump();