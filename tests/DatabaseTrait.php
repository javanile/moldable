<?php

namespace Javanile\Moldable\Tests;

use PDO;
use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;

trait DatabaseTrait
{
    protected function setUp()
    {
        $dsn = "mysql:dbname={$GLOBALS['DB_NAME']};host={$GLOBALS['DB_HOST']}";
        $this->pdo = new PDO($dsn, $GLOBALS['DB_USER'], $GLOBALS['DB_PASS']);
        $this->pdo->query("DROP DATABASE `database`");
        $this->pdo->query("CREATE DATABASE `database`");

        Database::resetDefault();
        Storable::resetAllClass();
    }

    protected function tearDown()
    {
        $this->pdo = null;
    }
}
