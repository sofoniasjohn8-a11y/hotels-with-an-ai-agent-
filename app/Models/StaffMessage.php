<?php

namespace App\Models;

use Database\Factories\StaffMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffMessage extends Model
{
    public const STATUS_UNREAD = 'unread';

    public const STATUS_READ = 'read';

    public const STATUS_RESOLVED = 'resolved';

    /** @use HasFactory<StaffMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'name',
        'phone',
        'message',
        'status',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}
