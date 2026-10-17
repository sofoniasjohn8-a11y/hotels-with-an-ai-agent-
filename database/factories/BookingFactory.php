<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'guest_id' => Guest::factory(),
            'room_type_id' => RoomType::factory(),
            'room_id' => null,
            'check_in' => today()->addDays(10),
            'check_out' => today()->addDays(11),
            'guest_count' => 1,
            'nightly_rate' => 0,
            'total_amount' => 0,
            'status' => Booking::STATUS_CONFIRMED,
            'notes' => null,
        ];
    }
}
