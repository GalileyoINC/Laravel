<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Communication\Infrastructure\Models\Notification;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFollowerAlert
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $total
 * @property int|null $used
 * @property Carbon $begin_at
 * @property Carbon|null $end_at
 * @property User|null $user
 */
class UserFollowerAlert extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'user_follower_alert';

    protected $casts = [
        'id_user' => 'int',
        'total' => 'int',
        'used' => 'int',
        'begin_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    protected $fillable = [
        'id_user',
        'total',
        'used',
        'begin_at',
        'end_at',
    ];

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }
}
