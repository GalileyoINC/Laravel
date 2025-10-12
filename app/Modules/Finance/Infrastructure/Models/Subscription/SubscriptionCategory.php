<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionCategory
 *
 * @property int $id
 * @property string $name
 * @property int|null $id_parent
 * @property int $position_no
 * @property Collection|Subscription[] $subscriptions
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

    public function subscriptions()
    {
        return $this->hasMany(App\Models\Subscription\Subscription::class, 'id_subscription_category');
    }
}
