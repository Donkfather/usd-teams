<?php

namespace Tests\Feature;

use App\Player;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagePlayersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function player_requires_a_team()
    {
        $this->postJson('api/players', factory(Player::class)->raw([
            'team_id' => null
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('team_id');

        $this->assertCount(0, Player::all());
    }

    /** @test */
    public function create_player()
    {
        $this->postJson('api/players', factory(Player::class)->raw())
            ->assertStatus(201);

        $this->assertCount(1, Player::all());
    }

    /** @test */
    public function update_player()
    {
        $this->withoutExceptionHandling();
        $player = factory(Player::class)->create();
        $team = factory(Team::class)->create();
        $this->putJson("api/players/{$player->id}", [
            'first_name' => "john",
            'last_name' => 'doe',
            'team_id' => $team->id
        ])->assertStatus(200)->assertJson([
            'first_name' => 'john',
            'last_name' => 'doe',
            'team_id' => $team->id
        ]);
        $player->refresh();
        $this->assertEquals('john', $player->first_name);
        $this->assertEquals('doe', $player->last_name);
        $this->assertTrue($player->team->is($team));
    }
}
