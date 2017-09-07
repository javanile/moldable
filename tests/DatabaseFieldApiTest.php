<?php

namespace Javanile\Moldable\Tests;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseFieldApiTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseGetPrimaryKey()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'debug'    => true,
        ]);

        $db->apply('test_table_1', [
            'primary_key_field' => $db::PRIMARY_KEY,
            'test_field_2'      => "",
        ]);

        $this->assertEquals($db->getPrimaryKey('test_table_1'), 'primary_key_field');

        $db->apply('test_table_2', [
            'primary_key_field' => $db::KEY,
            'test_field_2'      => "",
        ]);

        $this->assertEquals($db->getPrimaryKey('test_table_2'), 'primary_key_field');

        $db->apply('test_table_1', [
            'primary_key_field_other' => $db::PRIMARY_KEY,
            'test_field_2'      => "",
        ]);

        $this->assertEquals($db->getPrimaryKey('test_table_1'), 'primary_key_field_other');
    }
}
