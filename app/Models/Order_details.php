<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_details extends Model
{
    protected $table = 'order_details';
    protected $fillable = ['order_id','product_id','quantity','price'];

    function orders(){
        return $this->belongsTo(Order::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }
}
