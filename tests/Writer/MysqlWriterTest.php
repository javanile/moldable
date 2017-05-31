<?php

namespace Javanile\Moldable\Tests\Writer;

use Javanile\Producer;
use Javanile\Moldable\Writer\MysqlWriter;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class MysqlTest extends TestCase
{
    /*
    public function testDatabaseSetDebug()
    {
        $schema = [
            'People' => [
                'name' => '',
            ]
        ];

        $parser = new MysqlParser();

        $parser->parse($parser);

        $this->assertEquals($schema, [
            'People' => [
                'name' => [
                    'Field'  => 'name',
                    'Before' => false,
                ],
            ]
        ]);
    }
    */
}
