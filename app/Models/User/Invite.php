<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Invite
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_follower_list
 * @property string $email
 * @property string|null $name
 * @property string|null $phone_number
 * @property string|null $token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Subscription\FollowerList $follower_list
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereIdFollowerList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invite whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Invite extends Model
{
    use HasFactory;

    protected $table = 'invite';

    protected $casts = [
        'id_user' => 'int',
        'id_follower_list' => 'int',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_user',
        'id_follower_list',
        'email',
        'name',
        'phone_number',
        'token',
    ];

    public function follower_list()
    {
        return $this->belongsTo(\App\Models\Subscription\FollowerList::class, 'id_follower_list');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
