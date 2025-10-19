<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Database\Factories\InfluencerPageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfluencerPage
 *
 * @property int $id
 * @property int $id_subscription
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Subscription $subscription
 *
 * @method static \Database\Factories\InfluencerPageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerPage whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class InfluencerPage extends Model
{
    use HasFactory;

    protected $table = 'influencer_page';

    protected $casts = [
        'id_subscription' => 'int',
    ];

    protected $fillable = [
        'id_subscription',
        'title',
        'alias',
        'description',
        'image',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'id_subscription');
    }

    protected static function newFactory()
    {
        return InfluencerPageFactory::new();
    }
}
