<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    protected $connection = 'pgsqlmain';
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'status',
        'phone_no',
        'category',
        'description',
        'amount_credit',
        'amount_debit',
        'balance'
    ];
}
