<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(ProductsSeeders::class);
        $this->call(OrderDetailSeeder::class);
        $this->call(OrderSeeder::class);
        
        // $this->call([
        //     MenuItemSeeder::class,
        // ]);
    }

    
    
}
