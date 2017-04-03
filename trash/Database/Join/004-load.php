<?php

//
require_once 'common.php';

//
$db->dump('Person');

//
$db->dump('Address');

//
$Person0 = $db->load('Person', 1);

//
SchemaDB\Debug::varDump($Person0);

//
$Person1 = $db->load('Person', 1, array(
    'address_name_1' => $db->join('Address', 'address1'),
    'address_name_2' => $db->join('Address', 'address2'),
    'address_name_3' => $db->join('Address', 'address1'),
    'address_name_4' => $db->join('Address', 'address2'),
    'Address2.city',
));

//
SchemaDB\Debug::varDump($Person1);
