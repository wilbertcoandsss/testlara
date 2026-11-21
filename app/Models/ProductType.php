<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    // akan refer ke banyak product
    public function product(){
        return $this->hasMany(Product::class);
    }
}
