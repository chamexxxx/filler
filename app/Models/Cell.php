<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;

    /**
     * Get the field that owns the cell.
     */
    public function field()
    {
        return $this->belongsTo(Post::class);
    }
}
