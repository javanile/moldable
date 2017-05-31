<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class People extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $name = "";

    public $surname = "";

    public $age = 0;

    public $address = 0;
}
