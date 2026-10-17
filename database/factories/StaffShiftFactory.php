<?php

namespace Database\Factories;

use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffShift>
 */
class StaffShiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(8),
            'status' => StaffShift::STATUS_SCHEDULED,
            'notes' => null,
        ];
    }
}
