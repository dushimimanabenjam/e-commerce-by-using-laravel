<?php

namespace Database\Factories;

use App\Models\customer;
use App\Models\order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => customer::factory(),
            'status' => fake()->randomElement(['pending', 'in_production', 'quality_check', 'completed', 'shipped']),
            'total' => fake()->randomFloat(2, 50, 5000),
        ];
    }
}
