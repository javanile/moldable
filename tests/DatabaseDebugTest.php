<?php

namespace Javanile\Moldable\Tests;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseDebugTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseSetDebug()
    {
        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'debug' => true,
        ]);

        $this->assertEquals($db->getDebug(), true);

        $db->setDebug(0);

        $this->assertEquals($db->getDebug(), false);
    }
}