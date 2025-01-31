<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "batch_number",
        "manufacturing_date",
        "expiry_date",
        "base_price",
        "exchange_rate",
        "buy_price",
        "notes",
        "product_id",
        "brand_id",
        "no_of_units",
        "quantity",
        "invoice_no"
    ];
}
