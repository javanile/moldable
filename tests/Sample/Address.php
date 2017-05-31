<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class Address extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $route = "";

    public $city = "";

    public $zip_code = 0;
}
