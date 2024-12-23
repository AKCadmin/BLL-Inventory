<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagePermission extends Model
{
    protected $table ="page_permissions";

    // Permission Model (app/Models/Permission.php)
public function user()
{
    return $this->belongsTo(User::class);
}

public function page()
{
    return $this->belongsTo(Page::class);
}

}
