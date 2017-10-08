<?php

namespace Javanile\Moldable\Tests\Laravel;

use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class LaravelSocketTest extends TestCase
{
    use LaravelTrait;

    public function testLaravelInit()
    {
        People::getDatabase()->setPrefix('prefix_');
        $prefix = People::getDatabase()->getPrefix();
        People::resetClass();

        $people = new People();

        $table = People::getTable();

        $desc = People::getDatabase()->getTables();

        $people->store(['name' => 'Frank']);

        $sql = 'SELECT * FROM prefix_People WHERE id = 1';

        $row = People::getDatabase()->getRow($sql);

        $this->assertEquals($row['name'], 'Frank');
    }

    public function testGetResults()
    {
        $people = new People();

        $people->store(['name' => 'Frank']);

        $sql = 'SELECT * FROM People WHERE id = 1';

        $results = People::getDatabase()->getResults($sql);

        $this->assertEquals($results[0]['name'], 'Frank');
    }

    public function testGetResultsAsObjects()
    {
        $people = new People();

        $people->store(['name' => 'Frank']);

        $sql = 'SELECT * FROM People WHERE id = 1';

        $results = People::getDatabase()->getResultsAsObjects($sql);

        $this->assertEquals($results[0]->name, 'Frank');
    }

    public function testGetValues()
    {
        $people = new People();

        $people->store(['name' => 'Frank']);

        $sql = 'SELECT name FROM People WHERE id = 1';

        $column = People::getDatabase()->getValues($sql);

        $this->assertEquals($column, ['Frank']);
    }

    public function testGetValue()
    {
        $people = new People();

        $people->store(['name' => 'Frank']);

        $sql = 'SELECT name FROM People WHERE id = 1';

        $value = People::getDatabase()->getValue($sql);

        $this->assertEquals($value, 'Frank');
    }

    public function testTransact()
    {
        $people = new People();

        People::getDatabase()->transact();
        $people->store(['name' => 'Frank']);
        People::getDatabase()->rollback();

        $sql = 'SELECT name FROM People WHERE id = 1';
        $value = People::getDatabase()->getValue($sql);
        $this->assertEquals($value, 'Frank');

        People::getDatabase()->transact();
        $people->store(['name' => 'Frank']);
        People::getDatabase()->commit();

        $sql = 'SELECT name FROM People WHERE id = 1';
        $value = People::getDatabase()->getValue($sql);
        $this->assertEquals($value, 'Frank');
    }

    public function testOthers()
    {
        $quotedString = People::getDatabase()->quote('Hello World!');
        $this->assertEquals($quotedString, "'Hello World!'");
    }
}
