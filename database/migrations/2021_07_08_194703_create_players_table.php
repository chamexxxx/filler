<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->enum('id', [1, 2]);
            $table->uuid('game_id');
            $table->foreign('game_id')->references('id')->on('games');
            $table->enum('color', ['blue', 'green', 'cyan', 'red', 'magenta', 'yellow', 'white']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
