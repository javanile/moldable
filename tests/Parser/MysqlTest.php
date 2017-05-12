<?php

namespace Javanile\Moldable\Tests\Parser;

use Javanile\Producer;
use Javanile\Moldable\Parser\Mysql\Mysql;
use PHPUnit\Framework\TestCase;


Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class MysqlTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseSetDebug()
    {
        $parser = new
    }
}