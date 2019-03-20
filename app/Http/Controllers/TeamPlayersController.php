<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamPlayersController extends Controller
{
    public function store(Team $team, Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $player = $team->players()
            ->create($request->only([
                'first_name',
                'last_name'
            ]));

        return response()->json($player, 201);
    }

}
