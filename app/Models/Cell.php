<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cell extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'field_id', 'laravel_through_key'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the field that owns the cell.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}
