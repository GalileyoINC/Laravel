<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Database\Factories\ReactionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Reaction
 *
 * @property int $id
 * @property string $emoji
 * @property string|null $type
 * @property int|null $count
 * @property bool|null $is_user_reacted
 * @property-read Collection<int, \App\Models\Communication\SmsPool> $sms_pools
 * @property-read int|null $sms_pools_count
 *
 * @method static \Database\Factories\ReactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reaction whereEmoji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reaction whereId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ReactionFactory>
 */
class Reaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'reaction';

    protected $fillable = [
        'emoji',
        'type',
        'count',
        'is_user_reacted',
    ];

    public function sms_pools(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Communication\SmsPool::class, 'sms_pool_reaction', 'id_reaction', 'id_sms_pool')
            ->withPivot('id_user');
    }

    protected static function newFactory()
    {
        return ReactionFactory::new();
    }
}
