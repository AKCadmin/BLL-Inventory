<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPagePermission extends Model
{
    protected $fillable = [
        'user_id',
        'page_id',
        'permission_id',
        'company_id',
    ];

    protected $table = "user_page_permissions";
}
