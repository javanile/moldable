<?php

use Javanile\Handbook\Page;
use Javanile\Moldable\Database;

class Delete extends Page
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
            'dbname'   => 'postgres',
            'username' => 'postgres',
            'password' => 'postgres',
            'prefix'   => 'prefix_',
        ]);

        $database->insert('');
    }
}
