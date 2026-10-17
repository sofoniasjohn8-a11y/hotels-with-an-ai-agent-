<?php

namespace App\Policies;

use App\Models\RoomType;
use App\Models\User;

class RoomTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, RoomType $roomType): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, RoomType $roomType): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, RoomType $roomType): bool
    {
        return $user->isAdmin();
    }
}
