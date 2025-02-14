<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    protected $connection = "pgsqlmain";
    protected $table = "customer_transactions";

    protected $fillable = [
        'customer_id',
        'order_id',
        'amount',
        'transaction_type',
        'previous_credit_limit',
        'new_credit_limit',
        'description'
    ];
}
