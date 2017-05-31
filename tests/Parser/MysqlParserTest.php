<?php

namespace Javanile\Moldable\Tests\Parser;

use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class MysqlParserTest extends TestCase
{
    use MysqlParserTrait;

    public function testStringField()
    {
        $schema = [
            'people' => [
                'first_name' => '',
            ]
        ];

        $this->parser->parse($schema);

        $this->assertEquals($schema, [
            'people' => [
                'first_name' => [
                    'Field'    => 'first_name',
                    'First'    => true,
                    'Before'   => false,
                    'Key'      => '',
                    'Type'     => 'varchar(255)',
                    'Null'     => 'NO',
                    'Extra'    => '',
                    'Default'  => '',
                    'Relation' => null,
                ],
            ]
        ]);
    }

    public function testNumberField()
    {
        $schema = [
            'people' => [
                'age' => 0,
            ]
        ];

        $this->parser->parse($schema);

        $this->assertEquals($schema, [
            'people' => [
                'age' => [
                    'Field'    => 'age',
                    'First'    => true,
                    'Before'   => false,
                    'Key'      => '',
                    'Type'     => 'int(11)',
                    'Null'     => 'NO',
                    'Extra'    => '',
                    'Default'  => 0,
                    'Relation' => null,
                ],
            ]
        ]);
    }
}
