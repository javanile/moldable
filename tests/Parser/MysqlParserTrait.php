<?php

namespace Javanile\Moldable\Tests\Parser;

use Javanile\Moldable\Parser\MysqlParser;

trait MysqlParserTrait
{
    protected $parser = null;

    protected function setUp()
    {
        $this->parser = new MysqlParser();
    }

    protected function tearDown()
    {
        $this->parser = null;
    }
}
