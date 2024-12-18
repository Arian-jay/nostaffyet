<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderDetailSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all order IDs
        $orderIds = DB::table('orders')->pluck('id'); // Get all order IDs from the orders table

        // Get all product IDs
        $productIds = DB::table('products')->pluck('id'); // Get all product IDs from the products table

        foreach ($orderIds as $orderId) {
            // For each order, insert multiple order details (e.g., 1 to 5 products per order)
            foreach (range(1, rand(1, 5)) as $index) {
                DB::table('order_details')->insert([
                    'order_id' => $orderId, // Use the current order ID
                    'product_id' => $faker->randomElement($productIds), // Random product ID from available products
                    'quantity' => $faker->numberBetween(1, 5), // Random quantity (1 to 5)
                    'price' => $faker->randomFloat(2, 10, 50), // Random price between 10 and 50
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
