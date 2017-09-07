<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseSocketTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseUndefinedSocket()
    {
        $this->expectException('Javanile\\Moldable\\Exception');
        $this->expectExceptionMessageRegExp("/Socket class '[a-z0-9_\\\\]+' not found/i");

        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'socket'   => 'Marimba',
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);
    }

    public function testSocketQuote()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $string = $db->quote("this is a 'test string'");

        $this->assertTrue(is_string($string));
    }
}
