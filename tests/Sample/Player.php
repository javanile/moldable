<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class Player extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $name = '';

    public $team = '<< Team >>';
}
