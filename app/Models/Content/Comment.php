<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * @property int $id
 * @property int|null $id_sms_pool
 * @property int|null $id_user
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $id_parent
 * @property bool $is_deleted
 * @property Comment|null $comment
 * @property SmsPool|null $sms_pool
 * @property User|null $user
 * @property Collection|Comment[] $comments
 * @property Collection|UserPointHistory[] $user_point_histories
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';

    protected $casts = [
        'id_sms_pool' => 'int',
        'id_user' => 'int',
        'id_parent' => 'int',
        'is_deleted' => 'bool',
    ];

    protected $fillable = [
        'id_sms_pool',
        'id_user',
        'message',
        'id_parent',
        'is_deleted',
    ];

    public function comment()
    {
        return $this->belongsTo(App\Models\Content\Comment::class, 'id_parent');
    }

    public function sms_pool()
    {
        return $this->belongsTo(App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }

    public function comments()
    {
        return $this->hasMany(App\Models\Content\Comment::class, 'id_parent');
    }

    public function user_point_histories()
    {
        return $this->hasMany(App\Models\User\UserPointHistory::class, 'id_comment');
    }
}
