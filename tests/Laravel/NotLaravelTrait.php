<?php

namespace Javanile\Moldable\Tests\Laravel;

use Javanile\Moldable\Context;
use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;

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
