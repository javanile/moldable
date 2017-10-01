<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class ReadApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testFirstApi()
    {
        $known = new People();
        $known->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        $frank = People::first();
        $this->assertEquals($frank->age, 18);
    }

    public function testExistsApi()
    {
        $known = new People();
        $known->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        $exists = People::exists(['name' => 'FrankZZZ']);
        $this->assertEquals($exists, false);
    }

    public function testAllApi()
    {
        People::import([
            ['name' => 'Frank', 'surname' => 'White', 'age'=> 18],
            ['name' => 'Anand', 'surname' => 'Chess', 'age'=> 19],
            ['name' => 'React', 'surname' => 'Black', 'age'=> 21],
        ]);

        $all = People::all(['limit' => 2]);
        $this->assertEquals(2, count($all));
    }

    public function testFirstAdvancedApi()
    {
        People::import([
            ['name' => 'Frank', 'surname' => 'White', 'age'=> 18],
            ['name' => 'Anand', 'surname' => 'Chess', 'age'=> 19],
            ['name' => 'React', 'surname' => 'Chess', 'age'=> 21],
        ]);

        $first = People::first(['order' => 'age']);
        $this->assertEquals(18, $first->age);

        $first = People::first(['fields' => ['name', 'surname']]);
        $this->assertEquals($first, ['name' => 'Frank', 'surname' => 'White']);

        $first = People::first(['where' => ['surname' => 'Chess']]);
        $this->assertEquals('Anand', $first->name);

        $first = People::first(['where' => "surname LIKE 'Chess'"]);
        $this->assertEquals('Anand', $first->name);
    }

    public function testMinAdvancedApi()
    {
        People::import([
            ['name' => 'Anand', 'surname' => 'Chess', 'age'=> 19],
            ['name' => 'Frank', 'surname' => 'White', 'age'=> 18],
            ['name' => 'React', 'surname' => 'Chess', 'age'=> 21],
        ]);

        $min = People::min('age');
        $this->assertEquals(18, $min);

        $min = People::min(['order' => 'age']);
        $this->assertEquals(18, $min);

        $min = People::min(['fields' => ['name', 'surname']]);
        $this->assertEquals($min, ['name' => 'Anand', 'surname' => 'Chess']);

        $min = People::min(['where' => ['surname' => 'Chess']]);
        $this->assertEquals('Anand', $min->name);

        $min = People::min(['where' => "surname LIKE 'Chess'"]);
        $this->assertEquals('Anand', $min->name);
    }
}
