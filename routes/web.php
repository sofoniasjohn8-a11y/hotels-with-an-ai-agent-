<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Staff\BookingController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\GuestController;
use App\Http\Controllers\Staff\RoomController;
use App\Http\Controllers\Staff\RoomTypeController;
use App\Http\Middleware\EnsureStaff;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:staff-login')
        ->name('login.store');
});

Route::middleware(['auth', EnsureStaff::class])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('room-types', RoomTypeController::class)
            ->except('show')
            ->parameters(['room-types' => 'roomType']);
        Route::resource('rooms', RoomController::class)->except('show');
        Route::resource('guests', GuestController::class)->except('show');

        Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::patch('bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.check-in');
        Route::patch('bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.check-out');
        Route::resource('bookings', BookingController::class)->except('show');
    });
