<?php

//
require_once 'common.php';

//
use Javanile\SchemaDB;

//
class MyDatetime extends SchemaDB\Storable
{

    public $data = '2012-01-01';
    public $altro = self::DATE;
    public $campo22 = self::TIMESTAMP;

}

//
//MyDatetime::drop('confirm');

//
$item = new MyDatetime;

//
$item->store();

//
MyDatetime::dump();