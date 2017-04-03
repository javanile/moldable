<?php

namespace Javanile\Producer\Tests;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Producer\\Tests\\' => __DIR__]);

final class DatabaseTest extends TestCase
{
    use DatabaseTrait;

    public function testNewDatabaseNoPrefix()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_USER'],
        ]);

        Producer::log($db->getTables());
    }
}
