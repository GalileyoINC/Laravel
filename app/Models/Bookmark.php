<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the bookmark
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User\User::class, 'user_id');
    }

    /**
     * Get the post that is bookmarked
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Communication\SmsPool::class, 'post_id');
    }
}
