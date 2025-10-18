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
 * Class Invite
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_follower_list
 * @property string $email
 * @property string|null $name
 * @property string|null $phone_number
 * @property string|null $token
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property FollowerList $follower_list
 * @property User $user
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
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
