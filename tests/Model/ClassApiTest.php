<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;
use Javanile\Moldable\Readable;
use Javanile\Moldable\Storable;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\DatabaseTrait;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class ClassApiTest extends TestCase
{
    use DatabaseTrait;

    public function testClassApi()
    {
        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
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

    public function testModelApi()
    {
        $model = People::getModel();

        $this->assertEquals($model, 'People');
    }
}
