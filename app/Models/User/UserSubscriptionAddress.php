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
 * Class UserSubscriptionAddress
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_subscription
 * @property string|null $zip
 * @property-read \App\Models\Subscription\Subscription $subscription
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscriptionAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscriptionAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscriptionAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscriptionAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscriptionAddress whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscriptionAddress whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscriptionAddress whereZip($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserUserSubscriptionAddressFactory>
 */
class UserSubscriptionAddress extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'user_subscription_address';

    protected $casts = [
        'id_user' => 'int',
        'id_subscription' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_subscription',
        'zip',
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
}
