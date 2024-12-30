<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        "batch_number",
        "manufacturing_date",
        "expiry_date",
        "base_price",
        "exchange_rate",
        "buy_price",
        "notes",
        "product_id"
    ];
}
