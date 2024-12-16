<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    public $table = 'roles';
   // public $timestamps = false;

    protected $fillable = [
        'role_name',
        'user_count',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    
}
