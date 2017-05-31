<?php

namespace Javanile\Moldable\Tests\Writer;

use Javanile\Producer;
use Javanile\Moldable\Writer\MysqlWriter;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class MysqlWriterTest extends TestCase
{
    public function testMysqlWriter()
    {
        $schema = [
            'People' => [
                'name' => '',
            ]
        ];

        $writer = new MysqlWriter();

        $column = $writer->columnDefinition(['Type' => 'int(11)']);

        $this->assertEquals($column, "int(11) NULL");
    }
}
