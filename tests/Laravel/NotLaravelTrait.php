<?php

namespace Javanile\Moldable\Tests\Laravel;

use PDO;
use Javanile\Producer;
use Javanile\Moldable\Context;
use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;
use Illuminate\Database\Capsule\Manager as Capsule;

trait NotLaravelTrait
{
    protected function setUp()
    {
        Context::useLaravel(false);
        Database::resetDefault();
        Storable::resetAllClass();
    }

    protected function tearDown()
    {
        Context::useLaravel(true);
    }
}
