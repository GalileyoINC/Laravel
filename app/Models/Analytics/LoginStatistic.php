<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LoginStatistic
 *
 * @property int $id
 * @property int $id_user
 * @property int|null $id_device
 * @property int $type
 * @property string|null $ip
 * @property string|null $user_agent
 * @property array<array-key, mixed>|null $data
 * @property string $created_at
 * @property-read \App\Models\User\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereIdDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginStatistic whereUserAgent($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AnalyticsLoginStatisticFactory>
 */
class LoginStatistic extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'login_statistic';

    protected $casts = [
        'id_user' => 'int',
        'id_device' => 'int',
        'type' => 'int',
        'data' => 'json',
    ];

    protected $fillable = [
        'id_user',
        'id_device',
        'type',
        'ip',
        'user_agent',
        'data',
    ];

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
