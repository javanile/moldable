<?php

namespace Javanile\Moldable\Tests;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseTest extends TestCase
{
    use DatabaseTrait;

    public function testNewDatabaseNoPrefix()
    {
        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $tables = $db->getTables();
        $this->assertEquals($tables, []);

        $db->execute("CREATE TABLE test_table_1 (field INT)");
        $tables = $db->getTables();
        $this->assertEquals($tables, ['test_table_1']);

        $db->execute("CREATE TABLE test_table_2 (field INT)");
        $tables = $db->getTables();
        $this->assertEquals($tables, ['test_table_1', 'test_table_2']);
    }

    public function testDatabaseConnectionMissingParamsException()
    {
        $this->expectException("Javanile\\Moldable\\Exception");
        $this->expectExceptionMessageRegExp("/Connection error/i");

        $db = new Database([]);
    }

    public function testDatabaseConnectionFewParamsException()
    {
        $this->expectException("Javanile\\Moldable\\Exception");
        $this->expectExceptionMessageRegExp("/Connection error.*dbname/i");

        $db = new Database([
            'host' => $GLOBALS['DB_HOST']
        ]);
    }

    public function testDatabaseConnectionUsernameMissingException()
    {
        $this->expectException("Javanile\\Moldable\\Exception");
        $this->expectExceptionMessageRegExp("/Connection error.*username/i");

        $db = new Database([
            'host'   => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
        ]);
    }
}