<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conversation>
 */
class ConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'channel' => fake()->randomElement(['chat', 'voice']),
            'language' => 'en',
            'transcript' => [],
            'started_at' => now(),
            'ended_at' => null,
            'outcome' => 'inquiry',
        ];
    }
}
