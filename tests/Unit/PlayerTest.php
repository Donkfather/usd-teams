<?php

namespace Tests\Unit;

use App\Player;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_firstName()
    {
        $player = factory(Player::class)->create([
            'first_name' => 'Player first'
        ]);

        $this->assertEquals('Player first', $player->first_name);
    }

    /** @test */
    public function has_lastName()
    {
        $player = factory(Player::class)->create([
            'last_name' => 'Player last'
        ]);

        $this->assertEquals('Player last', $player->last_name);
    }

    /** @test */
    public function has_team()
    {
        $team = factory(Team::class)->create();
        $player = factory(Player::class)->create([
            'team_id' => $team->id
        ]);

        $this->assertTrue($player->team->is($team));

    }
}
