<?php

namespace Javanile\Producer\Tests;

use PDO;
use Javanile\Producer;

trait DatabaseTrait
{
    protected function setUp()
    {
        $dsn = "mysql:dbname={$GLOBALS['DB_NAME']};host={$GLOBALS['DB_HOST']}";
        $this->pdo = new PDO($dsn, $GLOBALS['DB_USER'], $GLOBALS['DB_PASS']);
        $this->pdo->query("DROP DATABASE `database`");
        $this->pdo->query("CREATE DATABASE `database`");
    }

    protected function tearDown()
    {
        $this->pdo = null;
    }
}


