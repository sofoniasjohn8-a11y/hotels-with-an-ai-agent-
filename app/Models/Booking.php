<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CHECKED_IN = 'checked_in';

    public const STATUS_CHECKED_OUT = 'checked_out';

    public const STATUS_CANCELLED = 'cancelled';

    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'guest_id',
        'room_type_id',
        'room_id',
        'check_in',
        'check_out',
        'guest_count',
        'nightly_rate',
        'total_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'nightly_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (! $booking->booking_reference) {
                $booking->booking_reference = (string) Str::ulid();
            }
        });
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
