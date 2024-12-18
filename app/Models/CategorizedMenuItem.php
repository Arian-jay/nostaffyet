<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorizedMenuItem extends Model
{
    protected $table = 'categorized_menu_items';
    public $timestamps = false; // Views usually don't have timestamps
}
