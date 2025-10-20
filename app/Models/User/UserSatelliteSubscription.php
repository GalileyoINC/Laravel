<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserSatelliteSubscription
 *
 * @property int $id_user
 * @property int $id_subscription
 * @property-read \App\Models\Subscription\Subscription $subscription
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSatelliteSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSatelliteSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSatelliteSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSatelliteSubscription whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSatelliteSubscription whereIdUser($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserSatelliteSubscriptionFactory>
 */
class UserSatelliteSubscription extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'user_satellite_subscription';

    protected $casts = [
        'id_user' => 'int',
        'id_subscription' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_subscription',
    ];

    /**
     * @return BelongsTo<\App\Models\Subscription\Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\UserSatelliteSubscriptionFactory
    {
        return \Database\Factories\UserSatelliteSubscriptionFactory::new();
    }
}
