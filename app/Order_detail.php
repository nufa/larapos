<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    protected $guarded = [];
    //Model relationship ke Order_detail menggunakan hasMany
    public function order()
    {
    	return $this->belongsTo(Order::class);
    }
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
