<?php

namespace App\Http\Controllers;

use App\Player;
use App\Team;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'team_id' => 'required|exists:teams,id'
        ], [], [
            'team_id' => 'team'
        ]);

        Team::find($request->get('team_id'))->players()->create(
            $request->only(['first_name', 'last_name'])
        );

        return response()->json([], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Player $player
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Player $player)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'team_id' => 'required|exists:teams,id'
        ]);

        $player->update($request->only(['first_name', 'last_name', 'team_id']));

        return response()->json($player, 200);
    }
}
