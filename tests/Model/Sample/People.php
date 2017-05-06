<?php

namespace Javanile\Moldable\Tests\Model\Sample;

use Javanile\Moldable\Storable;

final class People extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $name = "";

    public $surname = "";

    public $age = 0;
}
