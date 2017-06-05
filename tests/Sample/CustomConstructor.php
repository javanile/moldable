<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class CustomConstructor extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $field1 = self::INT;

    public $field2 = self::TEXT;

    public function __construct($arg1, $arg2)
    {
        $this->init();

        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }
}
