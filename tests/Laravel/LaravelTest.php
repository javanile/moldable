<?php

namespace Javanile\Moldable\Tests\Laravel;

use Javanile\Moldable\Context;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class LaravelTest extends TestCase
{
    use LaravelTrait;

    public function testLaravelInit()
    {
        $people = new People();

        $people->store(['name' => 'Frank']);

        $sql = 'SELECT * FROM People WHERE id = 1';

        $row = People::getDatabase()->getRow($sql);

        $this->assertEquals($row['name'], 'Frank');
    }

    public function testCheckLaravel()
    {
        $check = Context::checkLaravel();

        $this->assertEquals($check, true);
    }
}
