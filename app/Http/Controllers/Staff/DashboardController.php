<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Staff/Dashboard', [
            'summary' => [
                'bookings' => Booking::query()
                    ->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_CHECKED_OUT])
                    ->count(),
                'guests' => Guest::query()->count(),
                'rooms' => Room::query()->count(),
                'roomTypes' => RoomType::query()->count(),
            ],
            'upcomingBookings' => Booking::query()
                ->with(['guest:id,name', 'roomType:id,name'])
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
                ->whereDate('check_in', '>=', today())
                ->orderBy('check_in')
                ->limit(5)
                ->get(['id', 'booking_reference', 'guest_id', 'room_type_id', 'check_in', 'check_out', 'status']),
        ]);
    }
}
