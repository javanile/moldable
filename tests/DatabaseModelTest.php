<?php

namespace Javanile\Moldable\Tests;

use Javanile\Producer;
use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseModelTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseModelSubmit()
    {
        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        //
        $db->apply(['model' => [
            'name' => '',
            'hook' => 0,
        ]]);
        $count = $db->getValue("SELECT COUNT(*) FROM model");
        $this->assertEquals($count, 0);

        //
        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 10,
        ]);
        $count = $db->getValue("SELECT COUNT(*) FROM model");
        $this->assertEquals($count, 1);

        //
        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 10,
        ]);
        $count = $db->getValue("SELECT COUNT(*) FROM model");
        $this->assertEquals($count, 1);

        //
        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 11,
        ]);
        $count = $db->getValue("SELECT COUNT(*) FROM model");
        $this->assertEquals($count, 2);
    }
}
