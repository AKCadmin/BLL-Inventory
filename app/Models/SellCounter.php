<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellCounter extends Model
{
    use SoftDeletes;
    protected $table = "sell_counter";

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer', 'id');
    }
}
