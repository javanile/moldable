<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\Model\Sample\People;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class ClassApiTest extends TestCase
{
    use DatabaseTrait;

    public function testSimpleStorable()
    {


        $config = People::getClassConfigInherit();

        Producer::log($config);



    }
}