<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => 1, // Assuming role_id 1 is for admin
            'name' => 'AEDRIAN PUSPUS',
            'email' => 'aedrian.puspus@carsu.edu.ph',
            'phone' => '09300618270',
            'password' => Hash::make('123123123'), // Use a secure password
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'role_id' => 3, // Assuming role_id 1 is for admin
            'name' => 'dodong',
            'email' => 'priscoarianjay159@gmail.com',
            'phone' => '0930061134',
            'password' => Hash::make('123123123'), // Use a secure password
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'role_id' => 3, // Assuming role_id 1 is for admin
            'name' => 'arian',
            'email' => 'arianjay159@gmail.com',
            'phone' => '093006181230',
            'password' => Hash::make('123123123'), // Use a secure password
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'role_id' => 1, // Assuming role_id 1 is for admin
            'name' => 'ARIAN JAY PRISCO',
            'email' => 'arianjay.carsu@carsu.edu.ph',
            'phone' => '09918823082',
            'password' => Hash::make('123123123'), // Use a secure password
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
