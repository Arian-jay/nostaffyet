<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected $fillable = [
        'transaction_items_id',
        'product_id',
        'product_name',
        'quantity',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'id');
    }
}
