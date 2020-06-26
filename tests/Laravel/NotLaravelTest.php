<?php

namespace Javanile\Moldable\Tests\Laravel;

use Javanile\Moldable\Context;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class NotLaravelTest extends TestCase
{
    use NotLaravelTrait;

    public function testUseStorableClass()
    {
        $this->expectException('Javanile\\Moldable\\Exception');
        $this->expectExceptionMessageRegExp('/database connection not found/i');

        $people = new People();

        $people->store(['name' => 'Frank']);
    }

    public function testCheckLaravel()
    {
        $check = Context::checkLaravel();

        $this->assertEquals($check, false);
    }
}
