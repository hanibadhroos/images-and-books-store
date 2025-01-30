<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = ['user_id','order_id','method','status','transaction_id','amount','commission','sellerAmount','seeler_id','available_at'];

    function user(){
        return $this->belongsTo(User::class);
    }

    function order(){
        return $this->belongsTo(Order::class);
    }
}
