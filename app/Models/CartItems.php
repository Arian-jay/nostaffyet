<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CartItems extends Model
{
    protected $table = 'cart_items';
    protected $fillable = [
        'cart_id', 
        'product_id', 
        'quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id');
    }
    public function Product()
    {
        return $this->hasOne(Product::class, 'id');
    }

}
