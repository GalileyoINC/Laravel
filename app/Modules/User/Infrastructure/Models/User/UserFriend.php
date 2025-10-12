<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\User\Infrastructure\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFriend
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_friend
 * @property User|null $user
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
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }
}
