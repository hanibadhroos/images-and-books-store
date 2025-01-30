<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'seller_id', 'order_id', 'amount',
        'site_commission', 'seller_amount', 'is_withdrawable',
        'withdrawal_available_at'
    ];

    protected $table = 'transactions';

    function user(){
        return $this->belongsTo(User::class);
    }
    function order(){
        return $this->belongsTo(Order::class);
    }
}
