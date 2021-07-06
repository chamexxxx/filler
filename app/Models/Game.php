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
}
