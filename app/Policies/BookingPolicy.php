<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->isStaff();
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->isStaff()
            && in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED], true);
    }

    public function checkIn(User $user, Booking $booking): bool
    {
        return $user->isStaff() && $booking->status === Booking::STATUS_CONFIRMED;
    }

    public function checkOut(User $user, Booking $booking): bool
    {
        return $user->isStaff() && $booking->status === Booking::STATUS_CHECKED_IN;
    }
}
