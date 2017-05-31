<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Readable;
use Javanile\Moldable\Storable;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\Sample\PlayerTeam;
use Javanile\Moldable\Tests\Sample\ItemCustomField;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class TableApiTest extends TestCase
{
    use DatabaseTrait;

    public function testClassApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
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