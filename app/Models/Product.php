<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $table = "products";
    public function company()
    {
        return $this->hasOne(company::class,'id','company_id');
    }
}
