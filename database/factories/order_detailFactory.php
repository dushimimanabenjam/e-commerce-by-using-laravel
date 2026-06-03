<?php

namespace Database\Factories;

use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<order_detail>
 */
class order_detailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => order::factory(),
            'product_id' => product::factory(),
            'quantity' => fake()->numberBetween(1, 20),
            'price' => fake()->randomFloat(2, 5, 500),
            'status' => fake()->randomElement(['pending', 'in_production', 'quality_check', 'completed']),
        ];
    }
}
