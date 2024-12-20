<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permission extends Model
{
    use HasFactory;
    //public $table = 'roles';
    // public $timestamps = false;

    protected $fillable = [
        'role_id',
        'status',
        'menus',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    // Assuming each permission relates to a single role
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    
}
