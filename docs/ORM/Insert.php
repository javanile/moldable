<?php

namespace Javanile\Moldable\Docs\ORM;

use Javanile\Handbook\Page;
use Javanile\Moldable\Database;

class Insert extends Page
{
    /**
     *
     */
    public function content()
    {
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

    public function before()
    {
        return 'delete';
    }
}
