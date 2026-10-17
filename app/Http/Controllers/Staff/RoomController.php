<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Room::class);

        return Inertia::render('Staff/Rooms/Index', [
            'rooms' => Room::query()->with('roomType:id,name')->withCount('bookings')->orderBy('room_number')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Room::class);

        return Inertia::render('Staff/Rooms/Form', [
            'room' => null,
            'roomTypes' => RoomType::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(RoomRequest $request): RedirectResponse
    {
        Room::query()->create($request->validated());

        return redirect()->route('staff.rooms.index')->with('success', 'Room created.');
    }

    public function edit(Room $room): Response
    {
        $this->authorize('update', $room);

        return Inertia::render('Staff/Rooms/Form', [
            'room' => $room,
            'roomTypes' => RoomType::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(RoomRequest $request, Room $room): RedirectResponse
    {
        $room->update($request->validated());

        return redirect()->route('staff.rooms.index')->with('success', 'Room updated.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $this->authorize('delete', $room);

        if ($room->bookings()->exists()) {
            throw ValidationException::withMessages([
                'room' => 'Rooms with booking history cannot be deleted.',
            ]);
        }

        $room->delete();

        return redirect()->route('staff.rooms.index')->with('success', 'Room deleted.');
    }
}
