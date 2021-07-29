<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\UsesUUID;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends BaseModel
{
    use HasFactory, UsesUUID;

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get the field associated with the game.
     */
    public function field(): HasOne
    {
        return $this->hasOne(Field::class);
    }

    /**
     * Get all of the cells for the game.
     */
    public function cells(): HasManyThrough
    {
        return $this->hasManyThrough(Cell::class, Field::class);
    }

    /**
     * Get the players for the game.
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
