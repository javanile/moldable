<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class PlayerTeam extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $name = '';

    public $surname = '';

    public $age = 0;

    public $leader = false;

    public $score = .0;

    public $time = self::TIME;

    public $date = self::DATE;
}
