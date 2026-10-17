<?php

namespace App\Policies;

use App\Models\Guest;
use App\Models\User;

class GuestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Guest $guest): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Guest $guest): bool
    {
        return $user->isStaff();
    }

    public function delete(User $user, Guest $guest): bool
    {
        return $user->isStaff();
    }
}
