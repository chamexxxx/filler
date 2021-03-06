<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Field;
use App\Models\Player;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Services\Filler\CellIterator;
use App\Services\Filler\Filler;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGameRequest $request
     * @return \Illuminate\Http\JsonResponse
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

        $cells = CellIterator::toArray(
            $filler->field->getCells(),
            ['playerNumber' => 'player_id'],
            ['field_id' => $field->id]
        );

        DB::table('cells')->insert($cells);

        return response()->json($game->only(['id']), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return $game->load('field', 'field.cells', 'players');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGameRequest $request
     * @param \App\Models\Game $game
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        $validated = $request->validated();

        $playerId = $validated['playerId'];
        $color = $validated['color'];

        if ($playerId !== $game->currentPlayerId) {
            return response('The player with the specified number cannot move now', 403);
        }

        $field = $game->field;

        $cells = $field->cells->map(function ($item) {
            $item->makeVisible('id');
            return $item;
        })->toArray();

        $filler = new Filler($field->width, $field->height, $cells);

        $stepResult = $filler->step($color, $playerId);
        $gameOver = $filler->isGameOver();

        if (!$stepResult && !$gameOver) {
            return response('The player with the specified number cannot select the specified color', 409);
        } else if ($gameOver) {
            $game->winnerPlayerId = $filler->getWinner();
        }

        CellIterator::each($filler->field->getCells(), function ($cell) {
            DB::table('cells')
                ->where('id', $cell->id)
                ->update([
                    'color'     => $cell->color,
                    'player_id' => $cell->playerNumber,
                ]);
        });

        DB::table('players')
            ->where('game_id', $game->id)
            ->where('id', $playerId)
            ->update(['color' => $color]);

        $game->currentPlayerId = $gameOver ? 0 : ($playerId === 1 ? 2 : 1);

        $game->save();
    }
}
