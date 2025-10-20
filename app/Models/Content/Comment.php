<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Content;

use Database\Factories\CommentFactory;
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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_parent
 * @property bool $is_deleted
 * @property-read Comment|null $comment
 * @property-read Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read Collection<int, Comment> $replies
 * @property-read int|null $replies_count
 * @property-read \App\Models\Communication\SmsPool|null $sms_pool
 * @property-read \App\Models\User\User|null $user
 * @property-read Collection<int, \App\Models\User\UserPointHistory> $user_point_histories
 * @property-read int|null $user_point_histories_count
 *
 * @method static \Database\Factories\CommentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereIdParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereIdSmsPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ContentCommentFactory>
 */
class Comment extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Comment, $this>
     */
    public function comment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'id_parent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Communication\SmsPool, $this>
     */
    public function sms_pool(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Comment, $this>
     */
    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'id_parent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Comment, $this>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'id_parent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\User\UserPointHistory, $this>
     */
    public function user_point_histories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\User\UserPointHistory::class, 'id_comment');
    }

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return CommentFactory::new();
    }
}
