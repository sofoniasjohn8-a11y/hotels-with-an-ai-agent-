<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_pages_require_a_staff_login(): void
    {
        $this->get('/staff/dashboard')->assertRedirect('/login');

        $this->get('/login')->assertOk()->assertInertia(fn ($page) => $page->component('Auth/Login'));
    }

    public function test_staff_can_sign_in_but_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create(['password' => 'a-long-test-password']);

        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'incorrect',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'a-long-test-password',
        ])->assertRedirect('/staff/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->get('/staff/dashboard')->assertOk()->assertInertia(
            fn ($page) => $page->component('Staff/Dashboard')->has('summary'),
        );

        $this->post('/staff/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_a_staff_account_can_be_created_without_seeding_a_shared_password(): void
    {
        $this->artisan('staff:create --role=admin')
            ->expectsQuestion('Staff name', 'Hotel Administrator')
            ->expectsQuestion('Staff email', 'admin@example.com')
            ->expectsQuestion('Password', '123456')
            ->expectsQuestion('Confirm password', '123456')
            ->assertSuccessful();

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();
        $this->assertSame(User::ROLE_ADMIN, $admin->role);
        $this->assertTrue(password_verify('123456', $admin->password));
        $this->assertNotSame('123456', $admin->password);
    }

    public function test_a_staff_password_cannot_be_shorter_than_six_characters(): void
    {
        $this->artisan('staff:create')
            ->expectsQuestion('Staff name', 'Hotel Receptionist')
            ->expectsQuestion('Staff email', 'receptionist@example.com')
            ->expectsQuestion('Password', '12345')
            ->expectsQuestion('Confirm password', '12345')
            ->assertExitCode(1);

        $this->assertDatabaseMissing('users', ['email' => 'receptionist@example.com']);
    }

    public function test_receptionists_can_manage_guest_operations_but_not_inventory(): void
    {
        $receptionist = User::factory()->create(['role' => User::ROLE_RECEPTIONIST]);
        $this->actingAs($receptionist);

        $this->get('/staff/rooms')->assertOk();
        $this->get('/staff/room-types')->assertOk();
        $this->get('/staff/rooms/create')->assertForbidden();
        $this->get('/staff/room-types/create')->assertForbidden();

        $guest = Guest::factory()->create();

        $this->put("/staff/guests/{$guest->id}", [
            'name' => 'Updated Guest',
            'email' => $guest->email,
            'phone' => $guest->phone,
            'notes' => null,
        ])->assertRedirect('/staff/guests');

        $this->assertDatabaseHas('guests', ['id' => $guest->id, 'name' => 'Updated Guest']);
    }

    public function test_admin_can_create_inventory_and_rejects_duplicate_room_numbers(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $this->post('/staff/room-types', [
            'name' => 'Garden Suite',
            'slug' => 'garden-suite',
            'description' => 'A quiet suite.',
            'capacity' => 3,
            'nightly_rate' => '125.00',
            'amenities' => ['Breakfast', 'Wi-Fi'],
            'photos' => ['https://example.com/suite.jpg'],
            'is_active' => true,
        ])->assertRedirect('/staff/room-types');

        $roomType = RoomType::query()->where('slug', 'garden-suite')->firstOrFail();
        $this->assertSame('125.00', $roomType->nightly_rate);
        $this->assertSame(['Breakfast', 'Wi-Fi'], $roomType->amenities);

        $roomData = [
            'room_type_id' => $roomType->id,
            'room_number' => '101',
            'floor' => 1,
            'status' => Room::STATUS_AVAILABLE,
            'notes' => null,
        ];

        $this->post('/staff/rooms', $roomData)->assertRedirect('/staff/rooms');
        $this->post('/staff/rooms', $roomData)->assertSessionHasErrors('room_number');
        $this->assertDatabaseCount('rooms', 1);
    }

    public function test_receptionist_can_create_and_transition_a_booking_using_the_configured_rate(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_RECEPTIONIST]));
        $guest = Guest::factory()->create();
        $roomType = RoomType::factory()->create([
            'capacity' => 3,
            'nightly_rate' => '125.00',
        ]);

        $this->post('/staff/bookings', [
            'guest_id' => $guest->id,
            'room_type_id' => $roomType->id,
            'check_in' => '2026-11-01',
            'check_out' => '2026-11-04',
            'guest_count' => 2,
            'notes' => null,
        ])->assertRedirect('/staff/bookings');

        $booking = Booking::query()->firstOrFail();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('125.00', $booking->nightly_rate);
        $this->assertSame('375.00', $booking->total_amount);

        $this->patch("/staff/bookings/{$booking->id}/check-in")
            ->assertRedirect('/staff/bookings');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => Booking::STATUS_CHECKED_IN]);

        $this->patch("/staff/bookings/{$booking->id}/check-out")
            ->assertRedirect('/staff/bookings');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => Booking::STATUS_CHECKED_OUT]);

        $this->patch("/staff/bookings/{$booking->id}/cancel")->assertForbidden();
    }

    public function test_booking_validation_enforces_room_capacity_and_configured_rates(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
        $guest = Guest::factory()->create();
        $roomType = RoomType::factory()->create(['capacity' => 2, 'nightly_rate' => null]);
        $data = [
            'guest_id' => $guest->id,
            'room_type_id' => $roomType->id,
            'check_in' => '2026-11-01',
            'check_out' => '2026-11-02',
            'guest_count' => 3,
        ];

        $this->from('/staff/bookings/create')
            ->post('/staff/bookings', $data)
            ->assertSessionHasErrors(['guest_count', 'room_type_id']);
        $this->assertDatabaseCount('bookings', 0);
    }
}
