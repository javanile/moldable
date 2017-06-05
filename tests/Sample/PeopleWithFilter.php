<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class PeopleWithFilter extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $name = "";

    public $surname = "";

    public $age = 0;

    public $address = 0;

    public function uppercaseName($value) {
        return strtoupper($value);
    }
}
