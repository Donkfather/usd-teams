<?php

use App\Player;
use App\Team;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'name' => 'Demo user',
            'email' => 'demo@usd.test',
            'password' => bcrypt('secret')
        ]);

        $teams = factory(Team::class, 5)->create();
        $teams->each(function ($team) {
           factory(Player::class, rand(1,6))->create([
               'team_id' => $team->id
           ]);
        });
    }
}
