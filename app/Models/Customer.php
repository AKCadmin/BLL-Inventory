<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $connection = "pgsqlmain";
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'credit_limit',
        'payment_days',
        'type_of_customer',
        'sale_user_status',
        'organization_id',
        'retail_shop',
        'customer',
        'opening_balance'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class,'organization_id');
    }
}
