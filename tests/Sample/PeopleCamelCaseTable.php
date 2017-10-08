<?php

namespace Javanile\Moldable\Tests\Sample;

final class PeopleCamelCaseTable extends CamelCaseTable
{
    public static $table = 'People';

    public $id = self::PRIMARY_KEY;

    public $name = '';

    public $surname = '';

    public $select = self::TEXT;

    public $age = 0;

    public $address = 0;
}
