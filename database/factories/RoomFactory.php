<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::factory(),
            'room_number' => fake()->unique()->numerify('###'),
            'floor' => fake()->numberBetween(1, 10),
            'status' => Room::STATUS_AVAILABLE,
            'notes' => null,
        ];
    }
}
