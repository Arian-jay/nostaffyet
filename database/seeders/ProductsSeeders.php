<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductsSeeders extends Seeder
{
    public function run()
    {
        // Initialize Faker
        $faker = Faker::create();

        // Holiday Limited Edition Products
        DB::table('products')->insert([
            ['category_id' => 1, 'name' => 'Biscoff Cream', 'description' => 'Delicious Biscoff cream for a perfect treat.', 'price' => 15.99, 'qty' => 50, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'name' => 'Dark Hot Chocolate', 'description' => 'Rich dark chocolate for the perfect winter drink.', 'price' => 20.99, 'qty' => 30, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'name' => 'Peppermint Cocoa', 'description' => 'Refreshing peppermint cocoa for the holidays.', 'price' => 18.50, 'qty' => 40, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Coffee Beverage Products
        DB::table('products')->insert([
            ['category_id' => 2, 'name' => 'Classic Black Coffee', 'description' => 'Bold and strong black coffee.', 'price' => 5.99, 'qty' => 100, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Classic Caramel Macchiato', 'description' => 'Sweet caramel macchiato with a rich coffee base.', 'price' => 7.50, 'qty' => 80, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Classic Hot Cocoa', 'description' => 'Warm and comforting classic hot cocoa.', 'price' => 6.00, 'qty' => 50, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Classic Latte', 'description' => 'A smooth blend of espresso and steamed milk.', 'price' => 6.50, 'qty' => 75, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Dalgona Coffee', 'description' => 'Whipped coffee for a creamy texture and rich flavor.', 'price' => 7.20, 'qty' => 60, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Icecream Espresso', 'description' => 'Espresso with a scoop of vanilla ice cream.', 'price' => 8.00, 'qty' => 50, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Matcha Latte', 'description' => 'Smooth matcha green tea latte with steamed milk.', 'price' => 7.00, 'qty' => 80, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Strawberry Latte', 'description' => 'Sweet and fruity strawberry latte with espresso and milk.', 'price' => 7.30, 'qty' => 70, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Foods & Snacks Products
        DB::table('products')->insert([
            ['category_id' => 3, 'name' => 'Chocolate Waffles', 'description' => 'Waffles filled with rich chocolate.', 'price' => 5.50, 'qty' => 40, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Cinnamon Roll', 'description' => 'Sweet cinnamon roll with icing.', 'price' => 4.99, 'qty' => 50, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'created_at' => now(), 'updated_by' => 1, 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Club Sandwich with Fries', 'description' => 'Classic club sandwich served with crispy fries.', 'price' => 8.99, 'qty' => 30, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Korean Egg Sandwich', 'description' => 'Korean style egg sandwich with a flavorful twist.', 'price' => 6.50, 'qty' => 40, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Strawberry Shortcake', 'description' => 'Delicious shortcake topped with fresh strawberries.', 'price' => 5.00, 'qty' => 60, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Coffee Beans & Ground Coffee Products
        DB::table('products')->insert([
            ['category_id' => 4, 'name' => 'Cofi Premium Roast Series - Plum', 'description' => 'Premium roasted coffee with a hint of plum flavor.', 'price' => 12.99, 'qty' => 50, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'name' => 'Cofi Premium Roast Series - Honey', 'description' => 'Premium roasted coffee with a touch of honey flavor.', 'price' => 13.99, 'qty' => 50, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'name' => 'Cofi Premium Roast Series - Chocolate', 'description' => 'Rich coffee with a hint of chocolate flavor.', 'price' => 14.50, 'qty' => 40, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'name' => 'Cofi Premium Roast Series - Berry', 'description' => 'Roasted coffee with a touch of berry flavor.', 'price' => 13.50, 'qty' => 50, 'availability' => true, 'image' => 'https://via.placeholder.com/150', 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
