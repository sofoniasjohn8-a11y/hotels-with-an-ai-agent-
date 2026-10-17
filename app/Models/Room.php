<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    public const STATUS_AVAILABLE = 'available';

    public const STATUS_OCCUPIED = 'occupied';

    public const STATUS_MAINTENANCE = 'maintenance';

    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'room_number',
        'floor',
        'status',
        'notes',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
