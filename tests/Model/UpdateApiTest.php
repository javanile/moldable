<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\Sample\People;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class UpdateApiTest extends TestCase
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

        $people = new People();

        $people->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        $sql = "SELECT age FROM prefix_People WHERE id = 1";

        // update by id inline
        People::update(1, 'age', 19);
        $this->assertEquals($db->getValue($sql), 19);

        // update by one array
        People::update(['id' => 1, 'age' => 30]);
        $this->assertEquals($db->getValue($sql), 30);

        // update by id and values
        People::update(1, ['age' => 20]);
        $this->assertEquals($db->getValue($sql), 20);

        // update by query and values
        People::update(['name' => 'Frank'], ['age' => 21]);
        $this->assertEquals($db->getValue($sql), 21);

        // from db update by id inline
        $db->update('People', 1, 'age', 22);
        $this->assertEquals($db->getValue($sql), 22);

        // from db update by one array
        $db->update('People', ['id' => 1, 'age' => 40]);
        $this->assertEquals($db->getValue($sql), 40);

        // from db update by id and values
        $db->update('People', 1, ['age' => 23]);
        $this->assertEquals($db->getValue($sql), 23);

        // from db update by query and values
        $db->update('People', ['name' => 'Frank'], ['age' => 24]);
        $this->assertEquals($db->getValue($sql), 24);
    }
}
