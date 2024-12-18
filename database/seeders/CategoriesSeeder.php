<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Holiday Limited Edition'],
            ['id' => 2, 'name' => 'Coffee Beverage'],
            ['id' => 3, 'name' => 'Foods & Snacks'],
            ['id' => 4, 'name' => 'Coffee Beans & Ground Coffee'],
        ]);
    }
}
