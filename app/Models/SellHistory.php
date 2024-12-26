<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellHistory extends Model
{
    protected $fillable = [
        'batch_no',
        'hospital_price',
        'wholesale_price',
        'retail_price',
        'valid_from',
        'valid_to',
        'user_id',
        'action',
    ];
}
