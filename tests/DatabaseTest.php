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

    public function testNewDatabaseWrongParams()
    {
        $this->expectException("Javanile\\Moldable\\Exception");
        $this->expectExceptionMessageRegExp("/Connection error/i");

        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => 'wrong dbname',
            'username' => 'wrong username',
            'password' => 'wrong password',
        ]);
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
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
        ]);
    }

    public function testDatabaseEmptySchemaException()
    {
        $this->expectException("Javanile\\Moldable\\Exception");
        $this->expectExceptionMessageRegExp("/empty schema not allowed/i");

        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->apply([]);
    }

    public function testDatabaseExecuteWrongQuery()
    {
        $this->expectException("Javanile\\Moldable\\Exception");
        $this->expectExceptionMessageRegExp("/Query error/i");

        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->execute("NO SENSE SQL QUERY");
    }

    public function testDatabaseGetRow()
    {
        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->execute("CREATE TABLE test_table (test_field_1 INT, test_field_2 VARCHAR(255))");
        $db->execute("INSERT INTO test_table VALUES (:test_field_1, :test_field_2)", [
            ':test_field_1' => 100,
            ':test_field_2' => 'string line',
        ]);

        $row = $db->getRow("SELECT * FROM test_table");

        $this->assertEquals($row, ['test_field_1' => 100, 'test_field_2' => 'string line']);

        $benchmark = $db->benchmark();

        $this->assertArrayHasKey('elapse', $benchmark);
    }

    public function testHasDefaultDatabase()
    {
        $flag = Database::hasDefault();

        $this->assertEquals($flag, false);

        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $flag = Database::hasDefault();

        $this->assertEquals($flag, true);
    }

    public function testDatabaseCache()
    {
        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->setCache('key', 'value');
        $this->assertEquals($db->hasCache('key'), true);
        $this->assertEquals($db->getCache('key'), 'value');
    }
}
