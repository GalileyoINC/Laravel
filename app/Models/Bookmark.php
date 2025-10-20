<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Communication\SmsPool|null $post
 * @property-read User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bookmark whereUserId($value)
 *
 * @mixin \Eloquent
 */
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
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User\User::class, 'user_id');
    }

    /**
     * Get the post that is bookmarked
     * @return BelongsTo<\App\Models\Communication\SmsPool, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Communication\SmsPool::class, 'post_id');
    }
}
