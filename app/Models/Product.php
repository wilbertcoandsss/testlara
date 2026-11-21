<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['product_type_id', 'name'];
    // Satu product -> hanya akan punya satu tipe
    public function productType(){
        return $this->belongsTo(ProductType::class);
    }
}
