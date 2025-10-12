<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory;

    protected $table = 'session';

    protected $fillable = [
        'id',
        'expiresAt',
        'token',
        'createdAt',
        'updatedAt',
        'ipAddress',
        'userAgent',
        'userId',
    ];

    protected $casts = [
        'expiresAt' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    /**
     * Get the user that owns the session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    /**
     * Serialize to JSON (like YII)
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'access_token' => $this->token,
            'user' => $this->user ? $this->user->toArray() : null,
            'expiresAt' => $this->expiresAt,
        ];
    }
}
