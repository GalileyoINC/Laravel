<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPointHistory
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_user_point_settings
 * @property int|null $id_sms_pool
 * @property int|null $id_comment
 * @property int $quantity
 * @property Carbon $created_at
 * @property Comment|null $comment
 * @property SmsPool|null $sms_pool
 * @property User $user
 * @property UserPointSetting $user_point_setting
 */
class UserPointHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'user_point_history';

    protected $casts = [
        'id_user' => 'int',
        'id_user_point_settings' => 'int',
        'id_sms_pool' => 'int',
        'id_comment' => 'int',
        'quantity' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_user_point_settings',
        'id_sms_pool',
        'id_comment',
        'quantity',
    ];

    public function comment()
    {
        return $this->belongsTo(\App\Models\Content\Comment::class, 'id_comment');
    }

    public function sms_pool()
    {
        return $this->belongsTo(\App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function user_point_setting()
    {
        return $this->belongsTo(UserPointSetting::class, 'id_user_point_settings');
    }
}
