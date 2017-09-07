<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DatabaseTrait;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class ManageApiTest extends TestCase
{
    use DatabaseTrait;

    public function testDropApi()
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

        $schema = $db->desc();

        $this->assertEquals($schema, [
            'People' => [
                'id' => [
                    'Field'   => 'id',
                    'Type'    => 'int(11)',
                    'Null'    => 'NO',
                    'Key'     => 'PRI',
                    'Default' => null,
                    'Extra'   => 'auto_increment',
                    'First'   => true,
                    'Before'  => false,
                ],
                'name' => [
                    'Field'   => 'name',
                    'Type'    => 'varchar(255)',
                    'Null'    => 'NO',
                    'Key'     => '',
                    'Default' => null,
                    'Extra'   => '',
                    'First'   => false,
                    'Before'  => 'id',
                ],
                'surname' => [
                    'Field'   => 'surname',
                    'Type'    => 'varchar(255)',
                    'Null'    => 'NO',
                    'Key'     => '',
                    'Default' => null,
                    'Extra'   => '',
                    'First'   => false,
                    'Before'  => 'name',
                ],
                'age' => [
                    'Field'   => 'age',
                    'Type'    => 'int(11)',
                    'Null'    => 'NO',
                    'Key'     => '',
                    'Default' => '0',
                    'Extra'   => '',
                    'First'   => false,
                    'Before'  => 'surname',
                ],
                'address' => [
                    'Field'   => 'address',
                    'Type'    => 'int(11)',
                    'Null'    => 'NO',
                    'Key'     => '',
                    'Default' => '0',
                    'Extra'   => '',
                    'First'   => false,
                    'Before'  => 'age',
                ],
            ],
        ]);

        People::drop('confirm');

        $schema = $db->desc();

        $this->assertEquals($schema, []);
    }

    public function testImportApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);

        People::import([
            ['name' => 'Frank'],
            ['name' => 'Alloy'],
            ['name' => 'Adami'],
        ]);

        $all = People::all(['order' => 'name']);

        $this->assertEquals($all, [
            ['id' => '3', 'name' => 'Adami', 'surname' => '', 'age' => '0', 'address' => '0'],
            ['id' => '2', 'name' => 'Alloy', 'surname' => '', 'age' => '0', 'address' => '0'],
            ['id' => '1', 'name' => 'Frank', 'surname' => '', 'age' => '0', 'address' => '0'],
        ]);
    }
}
