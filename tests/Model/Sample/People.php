<?php

namespace Javanile\Moldable\Tests\Model\Sample;

use Javanile\Producer;
use Javanile\Moldable\Database;
use Javanile\Moldable\Storable;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__]);

final class People extends Storable
{
    public $id = self::PRIMARY_KEY;
}
