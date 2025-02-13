<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table="invoice";

    public function sellCounter()
    {
        return $this->hasMany(SellCounter::class, 'order_id','order_id');
    }
}
