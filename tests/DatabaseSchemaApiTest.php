<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseSchemaApiTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseSchemaDesc()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'debug'    => true,
        ]);

        $db->apply([
            'test_table_1' => [
                'test_field_1' => 0,
            ],
            'test_table_2' => [
                'test_field_2' => 0,
            ],
        ]);

        $this->assertEquals($db->desc(), [
            'test_table_1' => [
                'test_field_1' => [
                    'Field'    => 'test_field_1',
                    'Type'     => 'int(11)',
                    'Null'     => 'NO',
                    'Key'      => '',
                    'Default'  => '0',
                    'Extra'    => '',
                    'First'    => true,
                    'Before'   => false,
                ],
            ],
            'test_table_2' => [
                'test_field_2' => [
                    'Field'    => 'test_field_2',
                    'Type'     => 'int(11)',
                    'Null'     => 'NO',
                    'Key'      => '',
                    'Default'  => '0',
                    'Extra'    => '',
                    'First'    => true,
                    'Before'   => false,
                ],
            ],
        ]);
    }

    public function testDatabaseSchemaAlter()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'debug'    => true,
        ]);

        $db->alter([
            'test_table_1' => [
                'test_field_1' => 0,
            ],
            'test_table_2' => [
                'test_field_2' => 0,
            ],
        ]);

        $this->assertEquals($db->desc(), [
            'test_table_1' => [
                'test_field_1' => [
                    'Field'    => 'test_field_1',
                    'Type'     => 'int(11)',
                    'Null'     => 'NO',
                    'Key'      => '',
                    'Default'  => '0',
                    'Extra'    => '',
                    'First'    => true,
                    'Before'   => false,
                ],
            ],
            'test_table_2' => [
                'test_field_2' => [
                    'Field'    => 'test_field_2',
                    'Type'     => 'int(11)',
                    'Null'     => 'NO',
                    'Key'      => '',
                    'Default'  => '0',
                    'Extra'    => '',
                    'First'    => true,
                    'Before'   => false,
                ],
            ],
        ]);
    }
}
