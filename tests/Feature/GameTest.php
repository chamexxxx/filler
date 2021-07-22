<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GameTest extends TestCase
{
    public function createGame(): TestResponse
    {
        return $this->postJson('/api/game', ['width' => 5, 'height' => 5]);
    }

    public function testRequiredFieldsForCreatingGame()
    {
        $response = $this->postJson('/api/game');
        $response->assertStatus(400);
    }

    public function testCreateGameWithInvalidData()
    {
        $response = $this->postJson('/api/game', ['width' => 3, 'height' => 6]);
        $response->assertStatus(400);
    }

    public function testCreateGame()
    {
        $response = $this->createGame();
        $response->assertStatus(201)->assertJson(['id' => true]);
    }

    public function testGetGame()
    {
        $response = $this->createGame();
        $gameResponse = $this->getJson("/api/game/$response[id]");

        $gameResponse
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->whereAllType([
                    'id' => 'string',
                    'field.width' => 'integer',
                    'field.height' => 'integer',
                    'field.cells' => 'array',
                    'players' => 'array',
                    'currentPlayerId' => 'integer',
                    'winnerPlayerId' => 'integer'
                ]);
            })
            ->assertJson(function (AssertableJson $json) use ($gameResponse) {
                $countCells = count($gameResponse['field']['cells']);
                $countPlayers = count($gameResponse['players']);

                $cells = [];
                for ($i = 0; $i < $countCells; $i++) {
                    $cells["field.cells.$i.color"] = 'string';
                    $cells["field.cells.$i.playerId"] = 'integer';
                }

                $players = [];
                for ($i = 0; $i < $countPlayers; $i++) {
                    $players["players.$i.id"] = 'integer';
                    $players["players.$i.color"] = 'string';
                }

                return $json->whereAllType(array_merge($cells, $players))->etc();
            });
    }
}
