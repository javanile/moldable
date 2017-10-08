<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class Team extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $name = '';

    public $position = 0;

    public $score = .0;
}
