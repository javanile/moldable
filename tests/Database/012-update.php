<?php

//
require_once 'common.php';

//
$db->drop('People', 'confirm');

//
$db->alter('People', [
    'id'   => $db::PRIMARY_KEY,
    'name' => '',
    'age'  => 12,
]);

//
$db->insert('People', 'name', 'Frank Man');

/* Update by ID one field * /
$db->update('People', 1, 'age', 12);

/* Update by ID multiple fields * /
$db->update('People', 1, [
    'name' => 'Frank The Man',
    'age'  => 12,
]);

/* Update by conditions one field * /
$db->update('People', ['age' => 13], 'name', 'Thrint Man');

/* Update by conditions multiple fields * /
$db->update('People', [
    'name' => 'Manny',
    'age'  => 13,
], [
    'name' => 'Thrint Man',
    'age'  => 12,
]);

/* Update by where conditions one field * /
$db->update('People', ['where' => 'age = 0'], 'name', 'Thrint Man');

/* Update by where conditions and binding */
$db->update('People', [
    'where'   => 'age >= :newage',
    ':newage' => 12,
], [
    'name' => 'Thrint Man',
    'age'  => 14,
]);

/**/

// printout table record
$db->dump('People');
