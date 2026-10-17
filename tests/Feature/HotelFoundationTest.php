<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\StaffMessage;
use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_hotel_models_persist_their_relations(): void
    {
        $roomType = RoomType::factory()->create();
        $room = Room::factory()->for($roomType)->create();
        $guest = Guest::factory()->create();
        $booking = Booking::factory()->for($guest)->for($roomType)->create([
            'room_id' => $room->id,
        ]);

        $this->assertNotEmpty($booking->booking_reference);
        $this->assertTrue($booking->guest->is($guest));
        $this->assertTrue($booking->roomType->is($roomType));
        $this->assertTrue($booking->room->is($room));
    }

    public function test_shifts_conversations_and_staff_messages_can_be_created(): void
    {
        $shift = StaffShift::factory()->create();
        $conversation = Conversation::factory()->create([
            'transcript' => [['role' => 'guest', 'content' => 'Hello']],
        ]);
        $message = StaffMessage::factory()->for($conversation)->create();

        $this->assertTrue($shift->user->is(User::findOrFail($shift->user_id)));
        $this->assertSame('Hello', $conversation->fresh()->transcript[0]['content']);
        $this->assertTrue($message->conversation->is($conversation));
    }

    public function test_home_page_uses_the_inertia_welcome_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Welcome'));
    }
}
