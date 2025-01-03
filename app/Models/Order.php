<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table='orders';
    protected $fillable =['user_id','total_price','status'];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    function orderDetails(){
        return $this->hasMany(Order_details::class);
    }
}
