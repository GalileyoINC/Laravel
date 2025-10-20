<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class UserPointSetting
 *
 * @property int $id
 * @property string|null $title
 * @property int $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, UserPointHistory> $user_point_histories
 * @property-read int|null $user_point_histories_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointSetting whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserPointSettingFactory>
 */
class UserPointSetting extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'user_point_settings';

    protected $casts = [
        'price' => 'int',
    ];

    protected $fillable = [
        'title',
        'price',
    ];

    /**
     * @return HasMany<UserPointHistory, $this>
     */
    public function user_point_histories(): HasMany
    {
        return $this->hasMany(UserPointHistory::class, 'id_user_point_settings');
    }
}
