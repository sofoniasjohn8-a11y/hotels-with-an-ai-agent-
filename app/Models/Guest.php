<?php

namespace App\Models;

use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    /** @use HasFactory<GuestFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
