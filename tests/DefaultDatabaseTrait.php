<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;
use PDO;

trait DefaultDatabaseTrait
{
    protected $db = null;

    protected function setUp()
    {
        $dsn = "mysql:dbname={$GLOBALS['DB_NAME']};".
            "port={$GLOBALS['DB_PORT']};host={$GLOBALS['DB_HOST']}";

        $this->pdo = new PDO($dsn, $GLOBALS['DB_USER'], $GLOBALS['DB_PASS']);
        $this->pdo->query("DROP DATABASE `{$GLOBALS['DB_NAME']}`");
        $this->pdo->query("CREATE DATABASE `{$GLOBALS['DB_NAME']}`");

        Database::resetDefault();
        Storable::resetAllClass();

        $this->db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
            'prefix'   => 'prefix_',
        ]);
    }

    protected function tearDown()
    {
        $this->pdo = null;
    }
}
