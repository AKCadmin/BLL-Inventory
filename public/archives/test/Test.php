<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'test';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'customers',
        'accommodation',
        'check_in_date',
        'check_out_date',
        'billing_amount',
    ];

    // Specify the attributes that should be cast to native types
    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'billing_amount' => 'decimal:2',
    ];
}
