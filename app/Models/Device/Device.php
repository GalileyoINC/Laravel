<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Device;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 *
 * @property int $id
 * @property int $id_user
 * @property string $uuid
 * @property string|null $os
 * @property string|null $push_token
 * @property string|null $access_token
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $push_turn_on
 * @property User $user
 */
class Device extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
