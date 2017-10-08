<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\ItemCustomField;
use Javanile\Moldable\Tests\Sample\ItemCustomFieldCamelCaseTable;
use Javanile\Moldable\Tests\Sample\ItemCustomFieldUnderscoreTable;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\Sample\PeopleCamelCaseTable;
use Javanile\Moldable\Tests\Sample\PeopleUnderscoreTable;
use Javanile\Moldable\Tests\Sample\PlayerTeam;
use Javanile\Moldable\Tests\Sample\PlayerTeamCamelCaseTable;
use Javanile\Moldable\Tests\Sample\PlayerTeamUnderscoreTable;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class TableApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testClassApi()
    {
        $table = People::getTable();
        $this->assertEquals($table, 'prefix_People');

        $table = PlayerTeam::getTable();
        $this->assertEquals($table, 'prefix_PlayerTeam');

        $table = ItemCustomField::getTable();
        $this->assertEquals($table, 'prefix_ItemCustomField');
    }

    public function testCamelCaseConventionApi()
    {
        $table = PeopleCamelCaseTable::getTable();
        $this->assertEquals($table, 'prefix_people');

        $table = PlayerTeamCamelCaseTable::getTable();
        $this->assertEquals($table, 'prefix_playerTeam');

        $table = ItemCustomFieldCamelCaseTable::getTable();
        $this->assertEquals($table, 'prefix_itemCustomFieldCamelCaseTable');
    }

    public function testUnderscoreConventionApi()
    {
        $table = PeopleUnderscoreTable::getTable();
        $this->assertEquals($table, 'prefix_people');

        $table = PlayerTeamUnderscoreTable::getTable();
        $this->assertEquals($table, 'prefix_player_team');

        $table = ItemCustomFieldUnderscoreTable::getTable();
        $this->assertEquals($table, 'prefix_item_custom_field_underscore_table');
    }
}
