<?php

namespace App\Models;

use Database\Factories\StaffShiftFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffShift extends Model
{
    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_ON_DUTY = 'on_duty';

    public const STATUS_OFF_DUTY = 'off_duty';

    public const STATUS_CANCELLED = 'cancelled';

    /** @use HasFactory<StaffShiftFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'starts_at',
        'ends_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
