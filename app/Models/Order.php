<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Specify table name (optional if using Laravel conventions)
    protected $table = 'orders';

    // Specify fillable fields
    protected $fillable = [
        'customer_id',
        'staff_id',
        'total_amount',
        'status',
    ];

    // Relationships

    // Relationship to User as Customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
