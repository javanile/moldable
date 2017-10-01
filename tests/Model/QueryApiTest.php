<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class QueryApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testQueryApi()
    {
        $frank = new People();
        $frank->store([
            'name' => 'Frank',
            'surname' => 'White',
            'age' => 18,
        ]);
        $results = People::query([
            'age' => 18,
        ]);
        $this->assertEquals($results, [
            0 => $frank,
        ]);

        $amber = new People();
        $amber->store([
            'name' => 'Amber',
            'surname' => 'White',
            'age' => 19,
        ]);
        $results = People::query([
            'age' => 19,
        ]);
        $this->assertEquals($results, [
            0 => $amber,
        ]);

        $results = People::query([
            'surname' => 'White',
            'order' => 'name ASC',
        ]);
        $this->assertEquals($results, [
            0 => $amber,
            1 => $frank,
        ]);
    }

    public function testRawQueryApi()
    {
        $frank = new People();
        $frank->store([
            'name' => 'Frank',
            'surname' => 'White',
            'select' => 'Human',
            'age' => 18,
        ]);
        $train = new People();
        $train->store([
            'name' => 'Train',
            'surname' => 'Gnome',
            'select' => 'Orch',
            'age' => 400,
        ]);
        $results = People::raw("SELECT * FROM prefix_People WHERE `select` LIKE '%uma%'");
        $this->assertEquals($results[0]['select'], 'Human');
    }

    public function testAllApi()
    {
        $frank = new People();
        $frank->store([
            'name' => 'Frank',
            'surname' => 'White',
            'select' => 'Human',
            'age' => 18,
        ]);
        $train = new People();
        $train->store([
            'name' => 'Train',
            'surname' => 'Gnome',
            'select' => 'Orch',
            'age' => 400,
        ]);

        $all = People::all();

        $this->assertEquals('Javanile\Moldable\Tests\Sample\People', get_class($all[0]));
        $this->assertEquals('Javanile\Moldable\Tests\Sample\People', get_class($all[1]));
    }

    public function testFirstLastApi()
    {
        $frank = new People();
        $frank->store([
            'name' => 'Frank',
            'surname' => 'White',
            'select' => 'Human',
            'age' => 18,
        ]);
        $train = new People();
        $train->store([
            'name' => 'Train',
            'surname' => 'Gnome',
            'select' => 'Orch',
            'age' => 400,
        ]);

        $firstId = People::first('id');
        $lastId = People::last('id');
        $firstName = People::first('name');
        $lastName = People::last('name');
        $this->assertEquals(1, $firstId);
        $this->assertEquals(2, $lastId);
        $this->assertEquals('Frank', $firstName);
        $this->assertEquals('Train', $lastName);
    }
}
