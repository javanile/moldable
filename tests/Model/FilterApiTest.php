<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\Sample\PeopleWithFilter;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class FilterApiTest extends TestCase
{
    use DatabaseTrait;

    public function testFilterApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $frank = new PeopleWithFilter([
            'name' => 'Frank',
        ]);

        $frank = PeopleWithFilter::filter($frank, 'uppercase');

        $this->assertEquals($frank->name, 'FRANK');
    }
}
