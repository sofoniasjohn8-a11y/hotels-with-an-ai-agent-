<?php

namespace App\Models;

use Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    /** @use HasFactory<ConversationFactory> */
    use HasFactory;

    protected $fillable = [
        'channel',
        'language',
        'transcript',
        'started_at',
        'ended_at',
        'outcome',
    ];

    protected function casts(): array
    {
        return [
            'transcript' => 'array',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function staffMessages(): HasMany
    {
        return $this->hasMany(StaffMessage::class);
    }
}
