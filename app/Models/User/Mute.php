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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }
}
