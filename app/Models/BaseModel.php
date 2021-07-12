<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquence\Behaviours\CamelCasing;

class BaseModel extends Model
{
    use HasFactory, CamelCasing;
}
