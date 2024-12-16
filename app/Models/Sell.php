<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    protected $table = "sell";
    protected $fillable = [
        'sku',
        'batch_no',
        'hospital_price',
        'wholesale_price',
        'retail_price',
        'valid_from',
        'valid_to',
    ];
}
