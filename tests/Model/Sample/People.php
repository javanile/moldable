<?php

namespace Javanile\Moldable\Tests\Model\Sample;

use Javanile\Moldable\Storable;

final class People extends Storable
{
    static $__config = [
        'custom'  => 'ciaosd',
        'adamant' => 'yello',
    ];

    public $id = self::PRIMARY_KEY;

    public $name = "";

    public $surname = "";

    public $age = 0;
}
