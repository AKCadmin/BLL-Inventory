<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $table = "products";
    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }
}
