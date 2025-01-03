<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart_items extends Model
{
    protected $table = 'cart_items';
    protected $fillable = ['cart_id','product_id','quantity'];

    function cart(){
        return $this->belongsTo(Cart::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }
}
