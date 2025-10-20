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
 * Class MemberRequest
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_user_from
 * @property int|null $type
 * @property string|null $text
 * @property array<array-key, mixed>|null $params
 * @property int $is_active
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest whereIdUserFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberRequest whereType($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\MemberRequestFactory>
 */
class MemberRequest extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'member_request';

    protected $casts = [
        'id_user' => 'int',
        'id_user_from' => 'int',
        'type' => 'int',
        'params' => 'json',
        'is_active' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_user_from',
        'type',
        'text',
        'params',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_from');
    }
}
