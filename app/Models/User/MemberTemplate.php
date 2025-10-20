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
 * Class MemberTemplate
 *
 * @property int $id
 * @property int|null $id_admin
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $id_plan
 * @property string|null $email
 * @property string|null $member_key
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property-read User|null $user
 * @property-read UserPlan|null $user_plan
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereIdAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereIdPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberTemplate whereMemberKey($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\MemberTemplateFactory>
 */
class MemberTemplate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'member_template';

    protected $casts = [
        'id_admin' => 'int',
        'id_plan' => 'int',
        'expired_at' => 'datetime',
    ];

    protected $fillable = [
        'id_admin',
        'first_name',
        'last_name',
        'id_plan',
        'email',
        'member_key',
        'expired_at',
    ];

    public function user_plan(): BelongsTo
    {
        return $this->belongsTo(UserPlan::class, 'id_plan');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
}
