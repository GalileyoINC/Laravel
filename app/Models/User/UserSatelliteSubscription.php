<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
class UserSatelliteSubscription extends Model
{
    use HasFactory;

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

    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
