<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellCounter extends Model
{
    use SoftDeletes;
    protected $table = "sell_counter";

    public function customerList()
    {
        return $this->belongsTo(Customer::class, 'customer', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function company()
    {
        return $this->belongsTo(Organization::class, 'company_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'sell_id');
    }

    public function sellPrice()
    {
        return $this->belongsTo(Sell::class, 'batch_id', 'batch_id');
    }
}
