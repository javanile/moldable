<?php

namespace Javanile\Moldable\Tests\Parser;

use Javanile\Producer;
use Javanile\Moldable\Parser\Mysql;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class MysqlTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseSetDebug()
    {
        $schema = [
            'People' => [
                'name' => '',
            ]
        ];

        $parser = new Mysql();

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
}
