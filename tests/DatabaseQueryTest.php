<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class DatabaseQueryTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseRawQuery()
    {
        if (file_exists($log = __DIR__.'/database.log')) {
            unlink($log);
        }

        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
            'debug'    => true,
            'log'      => $log,
        ]);

        $db->import('raw_table', [
            ['item' => 'TK100', 'desc' => 'powerfull gadget'],
            ['item' => 'ZR290', 'desc' => 'soft and power tail'],
            ['item' => 'HH999', 'desc' => 'management man man man'],
        ]);

        $results = $db->raw("SELECT * FROM prefix_raw_table WHERE `desc` LIKE '%full%'");

        $this->assertEquals($results, [['item' => 'TK100', 'desc' => 'powerfull gadget']]);
    }
}
