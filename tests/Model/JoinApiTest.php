<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\Sample\Address;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class JoinApiTest extends TestCase
{
    use DatabaseTrait;

    public function testJoinApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $address = new Address();

        $address->store([
            'route' => 'Rt. Cavallo',
            'city'
        ]);

        $frank = new People();

        $frank->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
            'address' => $address->id,
        ]);

        $results = People::all([
            'name',
            'address' => Address::join()
        ]);

        $this->assertEquals($results, [
            0 => [
                'name' => 'Frank',
                'address__id' => '1',
                'address__route' => 'Rt. Cavallo',
                'address__city' => '',
                'address__zip_code' =>'0',
            ]
        ]);
    }
}
