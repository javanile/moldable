<?php

use Javanile\Handbook\Page;
use Javanile\Moldable\Database;

class Insert extends Page
{
    /**
     *
     */
    public function content()
    {
        echo 'TEST';
        $database = new Database([
            'type'     => 'pgsql',
            'host'     => 'postgres',
            'dbname'   => 'moldable',
            'username' => 'moldable',
            'password' => 'moldable',
            'prefix'   => 'prefix_',
        ]);

        $database->insert('');
    }
}
