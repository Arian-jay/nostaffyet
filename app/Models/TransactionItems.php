<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItems extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'total_amount',
    ];

    public function transaction()
    {
        return $this->belongsTo(transaction::class, 'id');
    }
}
