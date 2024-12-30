<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'pgsqlmain';
   protected $table = "products";
    // public function company()
    // {
    //     return $this->hasOne(Company::class,'id','company_id');
    // }
    public function organization()
    {
        return $this->hasOne(Organization::class,'id','company_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class,'id','brand_id');
    }
}
