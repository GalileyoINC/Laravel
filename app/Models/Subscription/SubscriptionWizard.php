<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionWizard
 *
 * @property int $id
 * @property int|null $id_user
 * @property array|null $settings
 * @property Carbon $created_at
 * @property User|null $user
 */
class SubscriptionWizard extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'subscription_wizard';

    protected $casts = [
        'id_user' => 'int',
        'settings' => 'json',
    ];

    protected $fillable = [
        'id_user',
        'settings',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
