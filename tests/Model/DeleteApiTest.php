<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\Sample\People;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class DeleteApiTest extends TestCase
{
    use DatabaseTrait;

    public function testUpdateApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        People::insert([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        $sql = "SELECT age FROM prefix_People WHERE id = 1";
        $this->assertEquals($db->getValue($sql), 18);

        People::delete(1);
        $this->assertEquals($db->getValue($sql), null);

    }
}
