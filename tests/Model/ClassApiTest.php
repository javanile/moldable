<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Readable;
use Javanile\Moldable\Storable;
use Javanile\Moldable\Tests\DatabaseTrait;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class ClassApiTest extends TestCase
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
        ]);

        People::resetClass();

        $adamant = Readable::getClassConfig('adamant');
        $this->assertEquals($adamant, true);

        $adamant = Storable::getClassConfig('adamant');
        $this->assertEquals($adamant, false);

        $adamant = People::getClassConfig('adamant');
        $this->assertEquals($adamant, false);
    }

    public function testClassGlobalApi()
    {
        $arrayOfExcludedFields = People::getClassGlobal('schema-excluded-fields');
        $this->assertEquals(is_array($arrayOfExcludedFields), true);
        $this->assertEquals(count($arrayOfExcludedFields) > 1, true);

        People::setClassConfig('name-of-owner', 'Cesare');
        $owner = People::getClassConfig('name-of-owner');
        $this->assertEquals($owner, 'Cesare');
    }

    public function testModelApi()
    {
        $model = People::getModel();

        $this->assertEquals($model, 'People');
    }
}
