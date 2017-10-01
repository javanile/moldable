<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class ItemCustomFieldUnderscoreTable extends UnderscoreTable
{
    public $id = self::PRIMARY_KEY;

    public $name = '';
}
