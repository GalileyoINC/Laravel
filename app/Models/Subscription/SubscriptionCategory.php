<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SubscriptionCategory
 *
 * @property int $id
 * @property string $name
 * @property int|null $id_parent
 * @property int $position_no
 * @property-read Collection<int, Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 *
 * @method static \Database\Factories\Subscription\SubscriptionCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionCategory whereIdParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionCategory wherePositionNo($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SubscriptionCategoryFactory>
 */
class SubscriptionCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'subscription_category';

    protected $casts = [
        'id_parent' => 'int',
        'position_no' => 'int',
    ];

    protected $fillable = [
        'name',
        'id_parent',
        'position_no',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'id_subscription_category');
    }
}
