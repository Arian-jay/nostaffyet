<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id';
    public $timestamps = false; // Set to true if you want to handle timestamps in this table

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
