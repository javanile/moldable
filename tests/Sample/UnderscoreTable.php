<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

class UnderscoreTable extends Storable
{
    public static $__config = [
        'table-name-conventions' => 'underscore',
        'field-name-conventions' => 'underscore',
        'exclude-fields'         => ['escludedField1', 'escludedField2'],
    ];

    public $escludedField1;

    public $escludedField2;

    public $realField1;

    public $realField2;
}
