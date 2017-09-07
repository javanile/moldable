<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class ItemCustomField extends Storable
{
    public static $__config = [
        'table-name-conventions' => 'upper-camel-case',
    ];

    public $id = self::PRIMARY_KEY;

    public $name = '';
}
