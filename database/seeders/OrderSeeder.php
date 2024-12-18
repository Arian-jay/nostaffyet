<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Faker\Factory as Faker;

// class OrderSeeder extends Seeder
// {
//     public function run()
//     {
//         $faker = Faker::create();

//         // Customer IDs
//         $customerIds = [2];
//         // Staff IDs
//         $staffIds = [4, 7, 10, 11, 12];

//         foreach ($customerIds as $customerId) {
//             DB::table('transactions')->insert([
//                 'customer_id' => 2,
//                 'total_amount' => 50, // Random total amount between 20 and 100
//                 'status' => 'pending', // Random status
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ],
//             [
//                 'customer_id' => 3,
//                 'total_amount' => 50, // Random total amount between 20 and 100
//                 'status' => 'pending', // Random status
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ],
//         );
//         }
//     }
// }
