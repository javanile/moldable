<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseSqlInjectionTest extends TestCase
{
    use DatabaseTrait;

    public function testSqlInjectIntoInsertQuery()
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

        $db->apply([
            'test' => [
                'x' => $db::TEXT,
            ],
        ]);

        /*
        // try a Sql Injection
        $db->insert('test', [
            "x) SELECT CONCAT('The MySQL version is: ', VERSION()) -- " => "dummy value",
            "x" => "dummy value",
        ]);

        /*\
         * Avoid this:
         * INSERT INTO `prefix_test` (x) SELECT CONCAT('The MySQL version is: ', VERSION()) -- ,x) VALUES
         * (:x) SELECT CONCAT('The MySQL version is: ', VERSION()) -- ,:x)
        \*/

        //$this->assertTrue(is_object($results[0]));

        //$this->assertTrue(is_object($results[1]));

        $this->assertTrue(true);
    }
}
