<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestRequest;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class GuestController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Guest::class);

        return Inertia::render('Staff/Guests/Index', [
            'guests' => Guest::query()->withCount('bookings')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Guest::class);

        return Inertia::render('Staff/Guests/Form', ['guest' => null]);
    }

    public function store(GuestRequest $request): RedirectResponse
    {
        Guest::query()->create($request->validated());

        return redirect()->route('staff.guests.index')->with('success', 'Guest created.');
    }

    public function edit(Guest $guest): Response
    {
        $this->authorize('update', $guest);

        return Inertia::render('Staff/Guests/Form', ['guest' => $guest]);
    }

    public function update(GuestRequest $request, Guest $guest): RedirectResponse
    {
        $guest->update($request->validated());

        return redirect()->route('staff.guests.index')->with('success', 'Guest updated.');
    }

    public function destroy(Guest $guest): RedirectResponse
    {
        $this->authorize('delete', $guest);

        if ($guest->bookings()->exists()) {
            throw ValidationException::withMessages([
                'guest' => 'Guests with booking history cannot be deleted.',
            ]);
        }

        $guest->delete();

        return redirect()->route('staff.guests.index')->with('success', 'Guest deleted.');
    }
}
