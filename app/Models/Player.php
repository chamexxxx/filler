<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * Get the game that owns the player.
     */
    public function post()
    {
        return $this->belongsTo(Game::class);
    }
}
