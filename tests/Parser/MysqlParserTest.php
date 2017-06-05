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

    public function testEnumField()
    {
        $schema = [
            'people' => [
                'title' => ['Mr.', 'Ms.'],
            ]
        ];

        $this->parser->parse($schema);

        $this->assertEquals($schema, [
            'people' => [
                'title' => [
                    'Field'    => 'title',
                    'First'    => true,
                    'Before'   => false,
                    'Key'      => '',
                    'Type'     => "enum('Mr.','Ms.')",
                    'Null'     => 'NO',
                    'Extra'    => '',
                    'Default'  => 'Mr.',
                    'Relation' => null,
                ],
            ]
        ]);
    }

    public function testDatetimeField()
    {
        $schema = [
            'table' => [
                'day' => '2000-01-01',
                'moment' => '00:00:01',
                'precise_moment' => '2000-01-01 00:00:01',
            ]
        ];

        $this->parser->parse($schema);

        $this->assertEquals($schema, [
            'table' => [
                'day' => [
                    'Field'    => 'day',
                    'First'    => true,
                    'Before'   => false,
                    'Key'      => '',
                    'Type'     => "date",
                    'Null'     => 'YES',
                    'Extra'    => '',
                    'Default'  => '2000-01-01',
                    'Relation' => null,
                ],
                'moment' => [
                    'Field'    => 'moment',
                    'First'    => false,
                    'Before'   => 'day',
                    'Key'      => '',
                    'Type'     => "time",
                    'Null'     => 'YES',
                    'Extra'    => '',
                    'Default'  => '00:00:01',
                    'Relation' => null,
                ],
                'precise_moment' => [
                    'Field'    => 'precise_moment',
                    'First'    => false,
                    'Before'   => 'moment',
                    'Key'      => '',
                    'Type'     => "datetime",
                    'Null'     => 'YES',
                    'Extra'    => '',
                    'Default'  => '2000-01-01 00:00:01',
                    'Relation' => null,
                ],
            ]
        ]);
    }
}
