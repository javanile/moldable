<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\Sample\People;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class QueryApiTest extends TestCase
{
    use DatabaseTrait;

    public function testQueryApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $frank = new People();
        $frank->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);
        $results = People::query([
            'age' => 18,
        ]);
        $this->assertEquals($results, [
            0 => $frank,
        ]);

        $amber = new People();
        $amber->store([
            'name'    => 'Amber',
            'surname' => 'White',
            'age'     => 19,
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
}
