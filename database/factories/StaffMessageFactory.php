<?php

namespace Database\Factories;

use App\Models\StaffMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffMessage>
 */
class StaffMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'conversation_id' => null,
            'name' => fake()->name(),
            'phone' => fake()->e164PhoneNumber(),
            'message' => fake()->sentence(),
            'status' => StaffMessage::STATUS_UNREAD,
        ];
    }
}
