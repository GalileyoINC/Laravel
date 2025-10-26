<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InfluencerAssistant
 *
 * @property int $id_influencer
 * @property int $id_assistant
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerAssistant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerAssistant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerAssistant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerAssistant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerAssistant whereIdAssistant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerAssistant whereIdInfluencer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfluencerAssistant whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\InfluencerAssistantFactory>
 */
class InfluencerAssistant extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'influencer_assistant';

    protected $casts = [
        'id_influencer' => 'int',
        'id_assistant' => 'int',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_influencer');
    }

    public function influencer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_influencer');
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_assistant');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\InfluencerAssistantFactory
    {
        return \Database\Factories\InfluencerAssistantFactory::new();
    }
}
