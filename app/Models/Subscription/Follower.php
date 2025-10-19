<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $invite_settings
 * @property-read FollowerList $followerList
 * @property-read FollowerList $follower_list
 * @property-read \App\Models\User\User $user
 * @property-read \App\Models\User\User $userFollower
 * @property-read \App\Models\User\User $userLeader
 *
 * @method static \Database\Factories\FollowerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereIdFollowerList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereIdUserFollower($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereIdUserLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereInviteSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereUpdatedAt($value)
 *
 * @mixin \Eloquent
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
        return $this->belongsTo(FollowerList::class, 'id_follower_list');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user_leader');
    }

    // Added to align with controller usage
    public function followerList()
    {
        return $this->belongsTo(FollowerList::class, 'id_follower_list');
    }

    public function userLeader()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user_leader');
    }

    public function userFollower()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user_follower');
    }

    protected static function newFactory()
    {
        return FollowerFactory::new();
    }
}
