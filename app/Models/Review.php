<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $table = 'reviews';
    protected $fillable =['product_id','user_id','rating','comment'];

    function prodcut(){
        return $this->belongsTo(Product::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
