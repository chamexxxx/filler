<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Field;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGameRequest;

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
        return $game->load('field');
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
