<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\UsesUUID;

class Game extends Model
{
    use HasFactory, UsesUUID;

    /**
     * Get the field associated with the game.
     */
    public function field()
    {
        return $this->hasOne(Field::class);
    }

    /**
     * Get the players for the game.
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
