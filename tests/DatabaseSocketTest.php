<?php

namespace Javanile\Moldable\Tests;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseSocketTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseUndefinedSocket()
    {
        $this->expectException("Javanile\\Moldable\\Exception");
        $this->expectExceptionMessageRegExp("/Socket class not found/i");

        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'socket' => 'Marimba',
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);
    }
}