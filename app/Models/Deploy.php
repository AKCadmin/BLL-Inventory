<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deploy extends Model
{
    protected $table = "deploy";

    protected $fillable = [
         'user_id',
         'db_name',
         'status'
     ];
}
