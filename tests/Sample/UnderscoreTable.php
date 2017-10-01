<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

class UnderscoreTable extends Storable
{
    public static $__config = [
        'table-name-conventions' => 'underscore',
    ];
}
