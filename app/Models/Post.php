<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // hanya attribute terpilih yg bisa diedit (whitelist)
    protected $fillable = ['name', 'caption', 'likes', 'is_archived', 'user_id', 'image'];

    # Dibuka semuanya
    // protected $fillable = [];

    // guarded => attribute yang tidak bisa diedit(blacklist)
    protected $guarded = ['id'];

    // satu post hanya akan dipunyai (belongs to) ke satu user
    public function user(){
        return $this->belongsTo(User::class);
    }
}
