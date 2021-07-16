<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Field;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGameRequest;
use App\Services\Filler\Field as Filler;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGameRequest $request)
    {
        $validated = $request->validated();

        $game = new Game;

        $field = new Field;
        $field->fill($validated);

        $game->save();
        $game->field()->save($field);

        $filler = new Filler($validated['width'], $validated['height']);

        $game->players()->saveMany([
            new Player([
                'id'      => 1,
                'game_id' => $game->id,
                'color'   => $filler->getCurrentColor(1)
            ]),
            new Player([
                'id'      => 2,
                'game_id' => $game->id,
                'color'   => $filler->getCurrentColor(2)
            ]),
        ]);

        $cells = $filler->toArray(
            ['playerNumber' => 'player_id'],
            ['field_id' => $field->id]
        );

        DB::table('cells')->insert($cells);

        return $game->only(['id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return $game->load('field', 'cells', 'players');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //
    }
}
