<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DatabaseTrait;
use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class ManageApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testDropApi()
    {
        $known = new People();

        $known->store([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ]);

        $expectedSchema = [
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
                'select' => [
                    'Field'   => 'select',
                    'Type'    => 'text',
                    'Null'    => 'NO',
                    'Key'     => '',
                    'Default' => null,
                    'Extra'   => '',
                    'First'   => false,
                    'Before'  => 'surname',
                ],
                'age' => [
                    'Field'   => 'age',
                    'Type'    => 'int(11)',
                    'Null'    => 'NO',
                    'Key'     => '',
                    'Default' => '0',
                    'Extra'   => '',
                    'First'   => false,
                    'Before'  => 'select',
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
        ];

        $schema = $this->db->desc();
        $this->assertEquals($schema, $expectedSchema);

        People::drop('not confirm');
        $schema = $this->db->desc();
        $this->assertEquals($schema, $expectedSchema);

        People::drop('confirm');
        $schema = $this->db->desc();
        $this->assertEquals($schema, []);
    }

    public function testImportApi()
    {
        People::import([
            ['name' => 'Frank'],
            ['name' => 'Alloy'],
            ['name' => 'Adami'],
        ]);

        $all = People::all(['order' => 'name']);

        $this->assertEquals($all, [
            ['id' => '3', 'name' => 'Adami', 'surname' => '', 'select' => '','age' => '0', 'address' => '0'],
            ['id' => '2', 'name' => 'Alloy', 'surname' => '', 'select' => '', 'age' => '0', 'address' => '0'],
            ['id' => '1', 'name' => 'Frank', 'surname' => '', 'select' => '', 'age' => '0', 'address' => '0'],
        ]);
    }

    public function testSubmitApi()
    {
        People::import([
            ['name' => 'Frank'],
            ['name' => 'Alloy'],
            ['name' => 'Adami'],
        ]);

        People::submit(['name' => 'Adami', 'surname' => '', 'age' => '0']);

        $all = People::all([
            'order'  => 'name',
            'fields' => ['id', 'name', 'surname', 'age']
        ]);

        $this->assertEquals($all, [
            ['id' => '3', 'name' => 'Adami', 'surname' => '', 'age' => '0'],
            ['id' => '2', 'name' => 'Alloy', 'surname' => '', 'age' => '0'],
            ['id' => '1', 'name' => 'Frank', 'surname' => '', 'age' => '0'],
        ]);

        People::submit(['name' => 'Kenus', 'surname' => '', 'age' => '0']);

        $all = People::all([
            'order'  => ['id' => 'DESC'],
            'fields' => ['id', 'name', 'surname', 'age']
        ]);

        $this->assertEquals($all, [
            ['id' => '4', 'name' => 'Kenus', 'surname' => '', 'age' => '0'],
            ['id' => '3', 'name' => 'Adami', 'surname' => '', 'age' => '0'],
            ['id' => '2', 'name' => 'Alloy', 'surname' => '', 'age' => '0'],
            ['id' => '1', 'name' => 'Frank', 'surname' => '', 'age' => '0'],
        ]);
    }

    public function testUpsertApi()
    {
        People::import([
            ['name' => 'Frank'],
            ['name' => 'Alloy'],
            ['name' => 'Adami'],
        ]);

        People::upsert(
            ['name' => 'Adami', 'surname' => ''],
            ['age' => 10]
        );

        $all = People::all([
            'order'  => 'name',
            'fields' => ['id', 'name', 'surname', 'age']
        ]);

        $this->assertEquals($all, [
            ['id' => '3', 'name' => 'Adami', 'surname' => '', 'age' => '10'],
            ['id' => '2', 'name' => 'Alloy', 'surname' => '', 'age' => '0'],
            ['id' => '1', 'name' => 'Frank', 'surname' => '', 'age' => '0'],
        ]);

        People::upsert(
            ['name' => 'Kenus', 'surname' => ''],
            ['age' => '10']
        );

        $all = People::all([
            'order'  => ['id' => 'DESC'],
            'fields' => ['id', 'name', 'surname', 'age']
        ]);

        $this->assertEquals($all, [
            ['id' => '4', 'name' => 'Kenus', 'surname' => '', 'age' => '10'],
            ['id' => '3', 'name' => 'Adami', 'surname' => '', 'age' => '10'],
            ['id' => '2', 'name' => 'Alloy', 'surname' => '', 'age' => '0'],
            ['id' => '1', 'name' => 'Frank', 'surname' => '', 'age' => '0'],
        ]);
    }
}
