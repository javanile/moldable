<?php

//
require_once '../../common.php';

//
require_once __BASE__.'/vendor/autoload.php';

//
use Javanile\SchemaDB;

//
$db = new SchemaDB\Database(array(
	'host' => $host,
	'user' => $user,
	'pass' => $pass,
	'name' => $name,
	'pref' => 'Test_Join_',
));

//
$db->setDebug(true);

//
//$db->drop('confirm');

//
$db->apply('Person', array(

    //
	'id' => $db::PRIMARY_KEY,
	//
	'name' => '',
	'surname' => "",
	'age' => 0,
	'address1' => 0,
	'address2' => 0,
));

//
$db->apply('Address', array(

    //
	'id' => $db::PRIMARY_KEY,

	//
	'name' => "",
	'latitude' => 0,
	'longitude' => 0,
	'city' => "",
));