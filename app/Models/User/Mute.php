<?php

declare(strict_types=1);

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\Subscription\Subscription|null $subscription
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mute query()
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\MuteFactory>
 */
class Mute extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'mute';

    protected $fillable = [
        'id_subscription',
        'id_user',
        'is_muted',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_muted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * @return BelongsTo<\App\Models\Subscription\Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\MuteFactory
    {
        return \Database\Factories\MuteFactory::new();
    }
}
