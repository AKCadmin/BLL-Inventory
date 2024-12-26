<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_History extends Model
{
    protected $table="purchase_history";
    protected $fillable = [
        'action',
        'details',
        'user_id',
        'batch_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
