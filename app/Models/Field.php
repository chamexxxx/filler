<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["width", "height"];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the game that owns the field.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the cells for the field.
     */
    public function cells()
    {
        return $this->hasMany(Cell::class);
    }
}
