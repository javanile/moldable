<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

class CamelCaseTable extends Storable
{
    public static $__config = [
        'table-name-conventions' => 'camel-case',
    ];
}
