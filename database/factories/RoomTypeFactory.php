<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RoomType>
 */
class RoomTypeFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'capacity' => fake()->numberBetween(1, 6),
            'nightly_rate' => fake()->randomFloat(2, 50, 500),
            'amenities' => [],
            'photos' => [],
            'is_active' => true,
        ];
    }
}
