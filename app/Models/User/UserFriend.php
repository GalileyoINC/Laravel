<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserFriendFactory>
 */
class UserFriend extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
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

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
