<?php

namespace Javanile\Moldable\Tests\Parser;

use PDO;
use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;
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
