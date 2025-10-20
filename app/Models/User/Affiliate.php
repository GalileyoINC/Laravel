<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Affiliate
 *
 * @property int $id
 * @property int $id_user_parent
 * @property int $id_user_child
 * @property bool|null $is_active
 * @property array<array-key, mixed>|null $params
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIdUserChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIdUserParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AffiliateFactory>
 */
class Affiliate extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'affiliate';

    protected $casts = [
        'id_user_parent' => 'int',
        'id_user_child' => 'int',
        'is_active' => 'bool',
        'params' => 'json',
    ];

    protected $fillable = [
        'id_user_parent',
        'id_user_child',
        'is_active',
        'params',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_parent');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\AffiliateFactory
    {
        return \Database\Factories\AffiliateFactory::new();
    }
}
