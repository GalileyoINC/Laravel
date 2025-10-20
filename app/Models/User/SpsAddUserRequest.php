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
 * Class SpsAddUserRequest
 *
 * @property int $id
 * @property int|null $id_user
 * @property string|null $token
 * @property array<array-key, mixed>|null $post_data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest wherePostData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpsAddUserRequest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SpsAddUserRequestFactory>
 */
class SpsAddUserRequest extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'sps_add_user_request';

    protected $casts = [
        'id_user' => 'int',
        'post_data' => 'json',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_user',
        'token',
        'post_data',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\SpsAddUserRequestFactory
    {
        return \Database\Factories\SpsAddUserRequestFactory::new();
    }
}
