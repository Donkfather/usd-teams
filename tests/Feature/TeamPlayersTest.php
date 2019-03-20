<?php

namespace Tests\Feature;

use App\Player;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamPlayersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_team_can_have_players()
    {
        $team = factory(Team::class)->create();
        $playerRaw = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ];

        $this->postJson("api/teams/{$team->id}/players", $playerRaw)
            ->assertStatus(201)
            ->assertJson([
                'first_name' => $playerRaw['first_name'],
                'last_name' => $playerRaw['last_name']
            ]);

        $this->assertCount(1, $team->players);
    }

    /** @test */
     public function a_player_requires_a_first_name()
     {
         $team = factory(Team::class)->create();
         $playerRaw = [
             'last_name' => $this->faker->lastName,
         ];

         $this->postJson("api/teams/{$team->id}/players", $playerRaw)
             ->assertStatus(422)
             ->assertJsonValidationErrors('first_name');
     }

    /** @test */
     public function a_player_requires_a_last_name()
     {
         $team = factory(Team::class)->create();
         $playerRaw = [
             'first_name' => $this->faker->firstName,
         ];

         $this->postJson("api/teams/{$team->id}/players", $playerRaw)
             ->assertStatus(422)
             ->assertJsonValidationErrors('last_name');
     }
}
