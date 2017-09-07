<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DatabaseTrait;
use Javanile\Moldable\Tests\Sample\ItemCustomField;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\Sample\PlayerTeam;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class TableApiTest extends TestCase
{
    use DatabaseTrait;

    public function testClassApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        $table = People::getTable();

        $this->assertEquals($table, 'prefix_People');

        $table = PlayerTeam::getTable();

        $this->assertEquals($table, 'prefix_PlayerTeam');

        $table = ItemCustomField::getTable();

        $this->assertEquals($table, 'prefix_ItemCustomField');
    }
}
