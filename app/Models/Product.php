<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table= 'products';
    protected $fillable = ['title','description','category_id','user_id','type','price','file_path','watermark_path'];

    function category(){
        return $this->belongsTo(Category::class);
    }
    function user(){
        return $this->belongsTo(User::class);
    }
    function cart_items(){
        return $this->hasMany(Cart_items::class);
    }
}
