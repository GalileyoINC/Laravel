<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionWizard
 *
 * @property int $id
 * @property int|null $id_user
 * @property array<array-key, mixed>|null $settings
 * @property string $created_at
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionWizard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionWizard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionWizard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionWizard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionWizard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionWizard whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionWizard whereSettings($value)
 *
 * @mixin \Eloquent
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
