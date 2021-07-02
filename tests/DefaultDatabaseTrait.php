<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;
use PDO;

trait DefaultDatabaseTrait
{
    use DatabaseTrait;

    protected $db = null;

    protected $log = null;

    protected function setUp()
    {
        /*
        $dsn = "mysql:dbname={$GLOBALS['DB_NAME']};".
            "port={$GLOBALS['DB_PORT']};host={$GLOBALS['DB_HOST']}";

        $this->pdo = new PDO($dsn, $GLOBALS['DB_USER'], $GLOBALS['DB_PASS']);
        $this->pdo->query("DROP DATABASE `{$GLOBALS['DB_NAME']}`");
        $this->pdo->query("CREATE DATABASE `{$GLOBALS['DB_NAME']}`");

        Database::resetDefault();
        Storable::resetAllClass();

        if (file_exists($this->log = __DIR__.'/database.log')) {
            unlink($this->log);
        }
        */
        $this->db = $this->getDatabaseInstance();
    }

    protected function tearDown()
    {
        /*
        $this->pdo = null;

        Database::resetDefault();
        Storable::resetAllClass();
        */
    }
}
