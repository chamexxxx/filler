<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\Concerns\UsesUUID;

class Game extends BaseModel
{
    use HasFactory, UsesUUID;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get the field associated with the game.
     */
    public function field()
    {
        return $this->hasOne(Field::class);
    }

    /**
     * Get all of the cells for the game.
     */
    public function cells()
    {
        return $this->hasManyThrough(Cell::class, Field::class);
    }

    /**
     * Get the players for the game.
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
