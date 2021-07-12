<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Field;
use App\Models\Cell;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGameRequest;
use App\Services\Field as FieldService;

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

        $cells = FieldService::generate($validated['width'], $validated['height'], function ($color) {
            $cell = new Cell;
            $cell->color = $color;

            return $cell;
        });

        $field->cells()->saveMany($cells);

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
