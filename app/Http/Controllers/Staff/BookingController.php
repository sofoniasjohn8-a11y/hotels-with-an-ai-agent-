<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Booking::class);

        return Inertia::render('Staff/Bookings/Index', [
            'bookings' => Booking::query()
                ->with(['guest:id,name', 'roomType:id,name', 'room:id,room_number'])
                ->orderByDesc('check_in')
                ->paginate(15),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Booking::class);

        return Inertia::render('Staff/Bookings/Form', [
            'booking' => null,
            'guests' => Guest::query()->orderBy('name')->get(['id', 'name', 'phone']),
            'roomTypes' => RoomType::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'capacity', 'nightly_rate']),
        ]);
    }

    public function store(BookingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $roomType = RoomType::query()->findOrFail($data['room_type_id']);
        $nights = Carbon::parse($data['check_in'])->diffInDays(Carbon::parse($data['check_out']));
        $rate = (float) $roomType->nightly_rate;

        Booking::query()->create([
            ...$data,
            'nightly_rate' => $rate,
            'total_amount' => round($rate * $nights, 2),
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        return redirect()->route('staff.bookings.index')->with('success', 'Booking created.');
    }

    public function edit(Booking $booking): Response
    {
        $this->authorize('update', $booking);

        if (! in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED], true)) {
            throw ValidationException::withMessages([
                'booking' => 'Only pending or confirmed bookings can be edited.',
            ]);
        }

        return Inertia::render('Staff/Bookings/Form', [
            'booking' => $booking,
            'guests' => Guest::query()->orderBy('name')->get(['id', 'name', 'phone']),
            'roomTypes' => RoomType::query()
                ->where(fn ($query) => $query->where('is_active', true)->orWhereKey($booking->room_type_id))
                ->orderBy('name')
                ->get(['id', 'name', 'capacity', 'nightly_rate']),
        ]);
    }

    public function update(BookingRequest $request, Booking $booking): RedirectResponse
    {
        if (! in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED], true)) {
            throw ValidationException::withMessages([
                'booking' => 'Only pending or confirmed bookings can be edited.',
            ]);
        }

        $data = $request->validated();
        $roomType = RoomType::query()->findOrFail($data['room_type_id']);
        $nights = Carbon::parse($data['check_in'])->diffInDays(Carbon::parse($data['check_out']));
        $rate = (float) $roomType->nightly_rate;

        $booking->update([
            ...$data,
            'nightly_rate' => $rate,
            'total_amount' => round($rate * $nights, 2),
        ]);

        return redirect()->route('staff.bookings.index')->with('success', 'Booking updated.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        return redirect()->route('staff.bookings.index')->with('success', 'Booking cancelled.');
    }

    public function checkIn(Booking $booking): RedirectResponse
    {
        $this->authorize('checkIn', $booking);
        $booking->update(['status' => Booking::STATUS_CHECKED_IN]);

        if ($booking->room_id !== null) {
            Room::query()->whereKey($booking->room_id)->update(['status' => Room::STATUS_OCCUPIED]);
        }

        return redirect()->route('staff.bookings.index')->with('success', 'Guest checked in.');
    }

    public function checkOut(Booking $booking): RedirectResponse
    {
        $this->authorize('checkOut', $booking);
        $booking->update(['status' => Booking::STATUS_CHECKED_OUT]);

        if ($booking->room_id !== null) {
            Room::query()->whereKey($booking->room_id)->update(['status' => Room::STATUS_AVAILABLE]);
        }

        return redirect()->route('staff.bookings.index')->with('success', 'Guest checked out.');
    }
}
