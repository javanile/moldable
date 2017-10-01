<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class ItemCustomFieldCamelCaseTable extends CamelCaseTable
{
    public $id = self::PRIMARY_KEY;

    public $name = '';
}
