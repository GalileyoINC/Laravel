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
 * Class AdminMember
 *
 * @property int $id
 * @property int|null $id_admin
 * @property int|null $id_member
 * @property int|null $id_plan
 * @property-read User|null $user
 * @property-read UserPlan|null $user_plan
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMember whereIdAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMember whereIdMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminMember whereIdPlan($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AdminMemberFactory>
 */
class AdminMember extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'admin_member';

    protected $casts = [
        'id_admin' => 'int',
        'id_member' => 'int',
        'id_plan' => 'int',
    ];

    protected $fillable = [
        'id_admin',
        'id_member',
        'id_plan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_member');
    }

    public function user_plan(): BelongsTo
    {
        return $this->belongsTo(UserPlan::class, 'id_plan');
    }
}
