<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserFollowerAlert
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $total
 * @property int|null $used
 * @property \Illuminate\Support\Carbon $begin_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert whereBeginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFollowerAlert whereUsed($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserFollowerAlertFactory>
 */
class UserFollowerAlert extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'user_follower_alert';

    protected $casts = [
        'id_user' => 'int',
        'total' => 'int',
        'used' => 'int',
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    protected $fillable = [
        'id_user',
        'total',
        'used',
        'begin_at',
        'end_at',
    ];

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\UserFollowerAlertFactory
    {
        return \Database\Factories\UserFollowerAlertFactory::new();
    }
}
