<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSubscriptionAddress
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_subscription
 * @property string|null $zip
 * @property Subscription $subscription
 * @property User $user
 */
class UserSubscriptionAddress extends Model
{
    use HasFactory;

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

    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
