<?php

namespace Tests\Unit;

use App\Player;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
     public function has_players()
     {
         $team = factory(Team::class)->create();
         factory(Player::class, 2)->create([
             'team_id' => $team->id
         ]);

         $this->assertCount(2, $team->players);
     }

     /** @test */
      public function has_name()
      {
         $team = factory(Team::class)->create([
             'name' => 'Team name'
         ]);

         $this->assertEquals('Team name', $team->name);
      }
}
