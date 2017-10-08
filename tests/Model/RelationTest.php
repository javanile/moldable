<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\ItemCustomField;
use Javanile\Moldable\Tests\Sample\Team;
use Javanile\Moldable\Tests\Sample\Player;
use Javanile\Moldable\Tests\Sample\People;
use Javanile\Moldable\Tests\Sample\PlayerTeam;
use Javanile\Moldable\Tests\Sample\ItemCustomFieldCamelCaseTable;
use Javanile\Moldable\Tests\Sample\PeopleCamelCaseTable;
use Javanile\Moldable\Tests\Sample\PlayerTeamCamelCaseTable;
use Javanile\Moldable\Tests\Sample\ItemCustomFieldUnderscoreTable;
use Javanile\Moldable\Tests\Sample\PeopleUnderscoreTable;
use Javanile\Moldable\Tests\Sample\PlayerTeamUnderscoreTable;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class RelationTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testClassRelation()
    {
        $desc = Player::desc();
        $this->assertEquals($desc['team']['Type'], 'int(11)');
    }

    public function testInsertRelation()
    {
        $player = new Player();
        $player->team = [ 'name' => 'Juventus', 'score' => 5.5 ];
        $player->store();

        $team = Team::load(1);

        $this->assertEquals($team->name, 'Juventus');
        $this->assertEquals($team->score, 5.5);
    }
}
