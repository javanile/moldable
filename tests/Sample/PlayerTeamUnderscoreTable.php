<?php

namespace Javanile\Moldable\Tests\Sample;

final class PlayerTeamUnderscoreTable extends UnderscoreTable
{
    public static $table = 'PlayerTeam';

    public $id = self::PRIMARY_KEY;

    public $name = '';

    public $surname = '';

    public $age = 0;

    public $leader = false;

    public $score = .0;

    public $time = self::TIME;

    public $date = self::DATE;
}
