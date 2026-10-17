<?php

namespace App\Models;

use Database\Factories\RoomTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    /** @use HasFactory<RoomTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'capacity',
        'nightly_rate',
        'amenities',
        'photos',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'photos' => 'array',
            'is_active' => 'boolean',
            'nightly_rate' => 'decimal:2',
        ];
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
