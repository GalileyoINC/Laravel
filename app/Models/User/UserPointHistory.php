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
 * Class UserPointHistory
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_user_point_settings
 * @property int|null $id_sms_pool
 * @property int|null $id_comment
 * @property int $quantity
 * @property string $created_at
 * @property-read \App\Models\Content\Comment|null $comment
 * @property-read \App\Models\Communication\SmsPool|null $sms_pool
 * @property-read User $user
 * @property-read UserPointSetting $user_point_setting
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory whereIdComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory whereIdSmsPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory whereIdUserPointSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPointHistory whereQuantity($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserPointHistoryFactory>
 */
class UserPointHistory extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
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

    /**
     * @return BelongsTo<\App\Models\Content\Comment, $this>
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Content\Comment::class, 'id_comment');
    }

    /**
     * @return BelongsTo<\App\Models\Communication\SmsPool, $this>
     */
    public function sms_pool(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * @return BelongsTo<UserPointSetting, $this>
     */
    public function user_point_setting(): BelongsTo
    {
        return $this->belongsTo(UserPointSetting::class, 'id_user_point_settings');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\UserPointHistoryFactory
    {
        return \Database\Factories\UserPointHistoryFactory::new();
    }
}
