<?php

namespace Javanile\Moldable\Tests\Sample;

use Javanile\Moldable\Storable;

final class AllNotations extends Storable
{
    public $id = self::PRIMARY_KEY;

    public $booleanTrue = true;

    public $booleanFalse = false;

    public $string = 'Hello World!';

    public $varchar = self::VARCHAR;

    public $text = self::TEXT;

    public $float = 3.14;

    public $enumWithNull = [null, 'A', 'B', 'C'];

    public $enumNotation = '<<["a", "b", "c"]>>';

    public $enum = ['A', 'B', 'C'];

    public $time = self::TIME;

    public $date = self::DATE;

    public $datetime = self::DATETIME;
}
