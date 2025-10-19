<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Database\Factories\UserSubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSubscription
 *
 * @property int $id_user
 * @property int $id_subscription
 * @property-read \App\Models\Subscription\Subscription $subscription
 * @property-read User $user
 *
 * @method static \Database\Factories\UserSubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereIdUser($value)
 *
 * @mixin \Eloquent
 */
class UserSubscription extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'user_subscription';

    protected $casts = [
        'id_user' => 'int',
        'id_subscription' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_subscription',
    ];

    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    protected static function newFactory()
    {
        return UserSubscriptionFactory::new();
    }
}
