<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Carbon\Carbon;
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
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Subscription $subscription
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
        return $this->belongsTo(App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    protected static function newFactory()
    {
        return InfluencerPageFactory::new();
    }
}
