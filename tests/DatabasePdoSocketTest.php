<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabasePdoSocketTest extends TestCase
{
    use DatabaseTrait;

    public function testGetResultsAsObjects()
    {
        $db = new Database([
            'socket'   => 'pdo',
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $db->apply('test_table_1', [
            'test_field_1' => 0,
            'test_field_2' => '',
        ]);

        $db->insert('test_table_1', [
            'test_field_1' => 1,
            'test_field_2' => 'first',
        ]);

        $db->insert('test_table_1', [
            'test_field_1' => 2,
            'test_field_2' => 'second',
        ]);

        $results = $db->getResultsAsObjects('SELECT * FROM prefix_test_table_1');

        $this->assertTrue(is_object($results[0]));

        $this->assertTrue(is_object($results[1]));
    }

    public function testGetValue()
    {
        $db = new Database([
            'socket'   => 'pdo',
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $db->apply('test_table_1', [
            'test_field_1' => 0,
            'test_field_2' => '',
        ]);

        $db->insert('test_table_1', [
            'test_field_1' => 1,
            'test_field_2' => 'my value',
        ]);

        $value = $db->getValue('SELECT test_field_2 FROM prefix_test_table_1 WHERE test_field_1=1');

        $this->assertEquals($value, 'my value');
    }

    public function testLastInsertId()
    {
        $db = new Database([
            'socket'   => 'pdo',
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $db->apply('test_table_1', [
            'test_field_1' => $db::PRIMARY_KEY,
            'test_field_2' => '',
        ]);

        $db->insert('test_table_1', [
            'test_field_2' => 'my value',
        ]);

        $lastId = $db->getLastId();

        $this->assertEquals($lastId, 1);
    }
}
