<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class UndefinedType extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $undefined = '<<@undefined>>';
}
