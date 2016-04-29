<?php

//
require_once 'common.php';

//
$results = $db->query('Person', [
    'field' => [
        'name',
        'surname',
        'a1_*' => $db->join(),
        'a2_*' => Address::join(),
    ],
]);

//
$db->dump($results);

