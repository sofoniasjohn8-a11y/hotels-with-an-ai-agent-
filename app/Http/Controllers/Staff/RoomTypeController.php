<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RoomTypeController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', RoomType::class);

        return Inertia::render('Staff/RoomTypes/Index', [
            'roomTypes' => RoomType::query()->withCount(['rooms', 'bookings'])->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', RoomType::class);

        return Inertia::render('Staff/RoomTypes/Form', ['roomType' => null]);
    }

    public function store(RoomTypeRequest $request): RedirectResponse
    {
        RoomType::query()->create($request->validated());

        return redirect()->route('staff.room-types.index')->with('success', 'Room type created.');
    }

    public function edit(RoomType $roomType): Response
    {
        $this->authorize('update', $roomType);

        return Inertia::render('Staff/RoomTypes/Form', ['roomType' => $roomType]);
    }

    public function update(RoomTypeRequest $request, RoomType $roomType): RedirectResponse
    {
        $roomType->update($request->validated());

        return redirect()->route('staff.room-types.index')->with('success', 'Room type updated.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        $this->authorize('delete', $roomType);

        if ($roomType->rooms()->exists() || $roomType->bookings()->exists()) {
            throw ValidationException::withMessages([
                'roomType' => 'Room types with rooms or bookings cannot be deleted.',
            ]);
        }

        $roomType->delete();

        return redirect()->route('staff.room-types.index')->with('success', 'Room type deleted.');
    }
}
