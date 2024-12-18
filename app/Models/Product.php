<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Specify table name (optional if using Laravel conventions)
    protected $table = 'products';

    // Specify fillable fields
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'price',
        'qty',
        'availability',
        'image',
        'updated_by',
    ];

    // Relationships

    // Relationship to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
