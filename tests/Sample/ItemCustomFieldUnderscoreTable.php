<?php

namespace Javanile\Moldable\Tests\Sample;

final class ItemCustomFieldUnderscoreTable extends UnderscoreTable
{
    public $id = self::PRIMARY_KEY;

    public $name = '';

    public $manyToMany = '<< ItemCustomField ** >>';
    public $oneToMany = '<< ItemCustomField * >>';
    public $oneToOne = '<< ItemCustomField >>';
}
