<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 */
class Field extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['width', 'height'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'game_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the game that owns the field.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the cells for the field.
     */
    public function cells(): HasMany
    {
        return $this->hasMany(Cell::class);
    }
}
