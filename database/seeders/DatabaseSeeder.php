<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\customer;
use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => true,
        ]);

        $categories = category::factory(10)->create();

        $products = product::factory(50)->sequence(fn ($sequence) => [
            'category_id' => $categories->random()->id,
        ])->create();

        $customers = customer::factory(20)->create();

        $orders = order::factory(30)->sequence(fn ($sequence) => [
            'customer_id' => $customers->random()->id,
        ])->create();

        $orders->each(function ($order) use ($products) {
            order_detail::factory()->create([
                'order_id' => $order->id,
                'product_id' => $products->random()->id,
            ]);
        });
    }
}
