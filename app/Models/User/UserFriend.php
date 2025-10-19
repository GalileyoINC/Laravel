<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFriend
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_friend
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFriend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFriend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFriend query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFriend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFriend whereIdFriend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFriend whereIdUser($value)
 *
 * @mixin \Eloquent
 */
class UserFriend extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'user_friend';

    protected $casts = [
        'id_user' => 'int',
        'id_friend' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_friend',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
