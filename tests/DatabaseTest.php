<?php

use PHPUnit\Framework\TestCase;

use Javanile\Moldable\Database;

final class DatabaseTest extends TestCase
{
    public function testNewDatabaseNoPrefix()
    {
        $db = new Database([
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_USER'],
        ]);


    }
}
