<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\Player;
use Javanile\Moldable\Tests\Sample\Team;
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
        $player->team = ['name' => 'Juventus', 'score' => 5.5];
        $player->store();

        $team = Team::load(1);

        $this->assertEquals($team->name, 'Juventus');
        $this->assertEquals($team->score, 5.5);
    }
}
