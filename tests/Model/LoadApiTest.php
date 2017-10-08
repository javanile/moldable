<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\Noindexmodel;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class LoadApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testLoadApi()
    {
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
        $known1 = new People();
        $known1->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);
        $known2 = new People();
        $known2->store([
            'name'    => 'Anand',
            'surname' => 'Black',
            'age'     => 20,
        ]);

        $frank = People::load([
            'name' => 'Frank',
        ]);

        $this->assertEquals($frank->age, 18);

        $anand = People::load([
            'where' => "surname LIKE '%lac%'",
        ]);

        $this->assertEquals($anand->age, 20);
    }

    public function testLoadByMainField()
    {
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
