<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Device;

use Database\Factories\DeviceFactory as RootDeviceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Device
 *
 * @property int $id
 * @property int $id_user
 * @property string $uuid
 * @property string|null $os
 * @property string|null $push_token
 * @property string|null $access_token
 * @property array<array-key, mixed>|null $params
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $push_turn_on
 * @property-read \App\Models\User\User $user
 *
 * @method static \Database\Factories\Device\DeviceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device wherePushToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device wherePushTurnOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereUuid($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\DeviceDeviceFactory>
 */
class Device extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'device';

    protected $casts = [
        'id_user' => 'int',
        'params' => 'json',
        'push_turn_on' => 'int',
    ];

    protected $hidden = [
        'push_token',
        'access_token',
    ];

    protected $fillable = [
        'id_user',
        'uuid',
        'os',
        'push_token',
        'access_token',
        'params',
        'push_turn_on',
    ];

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    protected static function newFactory(): RootDeviceFactory
    {
        return RootDeviceFactory::new();
    }
}
