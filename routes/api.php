<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::post('teams', 'TeamsController@store');
    Route::get('teams', 'TeamsController@index');
    Route::post('teams/{team}/players', 'TeamPlayersController@store');

    Route::post('players', 'PlayersController@store');
    Route::delete('players/{player}', 'PlayersController@destroy');
    Route::put('players/{player}', 'PlayersController@update');

});
