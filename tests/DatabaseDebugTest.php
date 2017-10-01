<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Moldable\Functions;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseDebugTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseSetDebug()
    {
        $db = new Database([
            'host' => $GLOBALS['DB_HOST'],
            'port' => $GLOBALS['DB_PORT'],
            'dbname' => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'debug' => true,
        ]);

        $this->assertEquals($db->isDebug(), true);

        $db->setDebug(0);

        $this->assertEquals($db->isDebug(), false);
    }

    public function testVarDumpFunction()
    {
        $this->expectOutputRegex('/^<pre.+pre>$/s');
        $var = ['key' => 'value'];
        Functions::varDump($var);
    }

    public function testGridDumpFunction()
    {
        $this->expectOutputRegex('/^<pre.+pre>$/s');
        $grid = ['key' => 'value'];
        Functions::dumpGrid($grid);
    }

    public function testBenchmarkFunction()
    {
        $this->expectOutputRegex('/^<pre.+pre>$/s');
        $grid = ['key' => 'value'];
        Functions::benchmark($grid);
    }
}
