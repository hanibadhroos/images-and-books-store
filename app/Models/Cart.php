<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table= 'carts';
    protected $fillable = ['user_id','status',];
    function cartItems(){
        return $this->hasMany(Cart_items::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
