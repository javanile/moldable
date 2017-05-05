<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\Model\Sample\People;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class StorableTest extends TestCase
{
    use DatabaseTrait;

    public function testSimpleStorable()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $people = new People();
    }
}