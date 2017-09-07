<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\Sample\Noindexmodel;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class LoadApiTest extends TestCase
{
    use DatabaseTrait;

    public function testLoadApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $known = new People();

        $known->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        // load by id inline
        $frank = People::load(1);
        $this->assertEquals($frank->age, 18);
    }

    public function testLoadByQuery()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $known = new People();

        $known->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        // load by id inline
        $frank = People::load(['name' => 'Frank']);
        $this->assertEquals($frank->age, 18);
    }

    public function testLoadByMainField()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $known = new Noindexmodel();

        $known->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        // load by id inline
        $frank = Noindexmodel::load('Frank');
        $this->assertEquals($frank->age, 18);
    }
}
