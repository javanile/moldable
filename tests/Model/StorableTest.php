<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DatabaseTrait;
use Javanile\Moldable\Tests\Sample\CustomConstructor;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class StorableTest extends TestCase
{
    use DatabaseTrait;

    public function testDebugMode()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        People::resetClass();

        People::setDebug(true);

        $this->assertEquals(People::isDebug(), true);
    }

    public function testMake()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $frank = People::make([
            'name' => 'Frank',
        ]);

        $this->assertEquals($frank->name, 'Frank');
    }

    public function testSimpleStorable()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        People::resetClass();

        $people = new People();

        $id = $people->store(['name' => 'Frank']);

        $this->assertEquals($id, 1);

        $row = $db->getRow('SELECT * FROM People WHERE id = 1');

        $this->assertEquals($row['name'], 'Frank');
    }

    public function testSimpleStorableTwo()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $frank = new People();
        $frank->store(['name' => 'Frank']);

        $carol = new People();
        $carol->store(['name' => 'Carol']);

        $names = $db->getValues('SELECT name FROM People');

        $this->assertEquals($names, ['Frank', 'Carol']);
    }

    public function testStorableUpdate()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $frank = new People();
        $frank->store(['name' => 'Frank']);

        $name = $db->getValue('SELECT name FROM People');
        $this->assertEquals($name, 'Frank');

        $frank->name = 'The New Frank';
        $frank->store();

        $name = $db->getValue('SELECT name FROM People');
        $this->assertEquals($name, 'The New Frank');
    }

    public function testStorableConstructorOverride()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $object = new CustomConstructor('arg1', 'arg1');

        $this->assertEquals($object->field1, 0);
    }

    public function testStorableMakeMap()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $people = People::make([
            'old_name' => 'Frank',
        ], [
            'old_name' => 'name',
        ]);

        $this->assertEquals($people->name, 'Frank');
    }

    public function testUtilApi()
    {
        $now = People::now();

        $this->assertEquals(is_string($now), true);
    }

    public function testDump()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $dump = People::dump();

        $this->assertEquals(is_string($dump), true);
    }

    public function testConnect()
    {
        $db1 = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix1_',
        ]);

        $db2 = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix2_',
        ]);

        $schema1 = $db1->desc();
        $schema2 = $db2->desc();
        $this->assertEquals($schema1, []);
        $this->assertEquals($schema2, []);

        People::connect($db1);
        $schema1 = $db1->desc();
        $schema2 = $db2->desc();
        $this->assertEquals($schema1, ['People' => People::desc()]);
        $this->assertEquals($schema2, []);

        People::connect($db2);
        $schema1 = $db1->desc();
        $schema2 = $db2->desc();
        $this->assertEquals($schema1, ['People' => People::desc()]);
        $this->assertEquals($schema2, ['People' => People::desc()]);
    }

    public function testCheckAdamant()
    {
        $isAdamant = People::isAdamantTable();

        $this->assertEquals($isAdamant, false);
    }
}
