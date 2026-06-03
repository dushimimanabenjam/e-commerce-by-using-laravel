<?php

namespace Database\Factories;

use App\Models\category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = category::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Building Materials', 'Lumber & Timber', 'Hardware & Tools',
            'Electrical', 'Plumbing', 'Paint & Coatings',
            'Flooring', 'Roofing', 'Insulation', 'Outdoor & Garden',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
        ];
    }
}
