<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\PeopleWithFilter;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class FilterApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testFilterApi()
    {
        $frank = new PeopleWithFilter([
            'name' => 'Frank',
        ]);

        $frank = PeopleWithFilter::filter($frank, 'uppercase');

        $this->assertEquals($frank->name, 'FRANK');

        $frank = PeopleWithFilter::filter($frank, 'notExistingMethod');

        $this->assertEquals($frank->name, 'FRANK');
    }
}
