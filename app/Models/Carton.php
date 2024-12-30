<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carton extends Model
{
    protected $fillable = [
        "batch_id",
        "carton_number",
        "no_of_items_inside",
        "missing_items",
    ];

    
}
