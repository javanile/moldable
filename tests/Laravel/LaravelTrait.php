<?php

namespace Javanile\Moldable\Tests\Laravel;

use PDO;
use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;
use Illuminate\Database\Capsule\Manager as Capsule;

trait LaravelTrait
{
    protected function setUp()
    {
        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $GLOBALS['DB_HOST'],
            //'port' => DB_PORT,
            'database'  => $GLOBALS['DB_NAME'],
            'username'  => $GLOBALS['DB_USER'],
            'password'  => $GLOBALS['DB_PASS'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();

        Database::resetDefault();
        Storable::resetAllClass();
    }

    protected function tearDown()
    {
        Capsule::connection()->disconnect();
    }
}
