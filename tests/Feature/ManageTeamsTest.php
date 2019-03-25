<?php

namespace Tests\Feature;

use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageTeamsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function create_team()
    {
        $this->signIn();

        $this->postJson('/api/teams', [
            'name' => "Team name"
        ])
            ->assertStatus(201)
            ->assertJson([
                'id' => 1,
                'name' => "Team name"
            ]);

        $this->assertCount(1, Team::all());
    }

    /** @test */
    public function a_team_requires_a_name()
    {
        $this->signIn();
        $this->postJson('/api/teams', [])
            ->assertJsonValidationErrors('name');

        $this->assertCount(0, Team::all());
    }

    /** @test */
     public function list_teams()
     {
        factory(Team::class, 10)->create();

         $this->signIn();
        $this->getJson('/api/teams')
            ->assertStatus(200)
            ->assertJsonCount(10);

        $this->assertCount(10, Team::all());
    }
}
