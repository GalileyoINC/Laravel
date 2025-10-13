<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Carbon\Carbon;
use Database\Factories\FollowerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Follower
 *
 * @property int $id
 * @property int $id_follower_list
 * @property int $id_user_leader
 * @property int $id_user_follower
 * @property bool|null $is_active
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property array|null $invite_settings
 * @property FollowerList $follower_list
 * @property User $user
 */
class Follower extends Model
{
    use HasFactory;

    protected $table = 'follower';

    protected $casts = [
        'id_follower_list' => 'int',
        'id_user_leader' => 'int',
        'id_user_follower' => 'int',
        'is_active' => 'bool',
        'invite_settings' => 'json',
    ];

    protected $fillable = [
        'id_follower_list',
        'id_user_leader',
        'id_user_follower',
        'is_active',
        'invite_settings',
    ];

    public function follower_list()
    {
        return $this->belongsTo(App\Models\Subscription\FollowerList::class, 'id_follower_list');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user_leader');
    }

    protected static function newFactory()
    {
        return FollowerFactory::new();
    }
}
