<?php

namespace Database\Factories;

use App\Models\category;
use App\Models\product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<product>
 */
class ProductFactory extends Factory
{
    protected $model = product::class;

    public function definition(): array
    {
        $names = [
            'Pine Timber Plank', 'Oak Wood Beam', 'Cedar Fence Post', 'Birch Plywood Sheet',
            'Maple Hardwood Floor', 'Red Brick 50-Pack', 'Concrete Block', 'Steel Rebar 12mm',
            'Galvanized Nails 1kg', 'Wood Screws 100-Pack', 'Angle Grinder', 'Circular Saw Blade',
            'PVC Pipe 2m', 'Copper Wire 50m', 'LED Flood Light', 'Wall Paint 5L',
            'Paint Brush Set', 'Roller Tray', 'Ceramic Floor Tile', 'Laminate Flooring Pack',
            'Asphalt Shingles', 'Roofing Felt Roll', 'Fiberglass Insulation', 'Spray Foam Kit',
            'Garden Hose 20m', 'Flower Pot Set', 'Mulch Bag 50L', 'Patio Stone Slab',
            'Door Handle Set', 'Cabinet Hinge 10-Pack', 'Window Lock', 'Deadbolt Lock',
            'Rubber Seal Strip', 'Silicone Caulk Tube', 'Drywall Sheet', 'Joint Compound Bucket',
            'Painters Tape 50m', 'Sandpaper Assortment', 'Hex Key Set', 'Wire Brush',
            'Extension Cord 15m', 'Power Strip 6-Outlet', 'Smoke Detector', 'Thermostat',
            'Shower Head', 'Faucet Cartridge', 'Toilet Flapper', 'Drain Cleaner Gel',
            'Work Gloves Pair', 'Safety Goggles', 'Dust Mask 20-Pack',
        ];

        $name = $this->faker->unique()->randomElement($names);

        $productImages = [
            'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=640&h=480&fit=crop',
            'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?w=640&h=480&fit=crop',
            'https://images.unsplash.com/photo-1567016432779-094069958ea5?w=640&h=480&fit=crop',
            'https://images.unsplash.com/photo-1581539250439-c96689b516dd?w=640&h=480&fit=crop',
            'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=640&h=480&fit=crop',
            'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=640&h=480&fit=crop',
        ];

        return [
            'name' => $name,
            'price' => fake()->randomFloat(2, 5, 500),
            'stock' => fake()->numberBetween(0, 200),
            'category_id' => category::factory(),
            'image' => fake()->randomElement($productImages),
        ];
    }
}
