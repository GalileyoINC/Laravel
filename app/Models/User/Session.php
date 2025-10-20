<?php

declare(strict_types=1);

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $expiresAt
 * @property string $token
 * @property \Illuminate\Support\Carbon $createdAt
 * @property \Illuminate\Support\Carbon $updatedAt
 * @property string|null $ipAddress
 * @property string|null $userAgent
 * @property int $userId
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Session whereUserId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SessionFactory>
 */
class Session extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'session';

    protected $fillable = [
        'id',
        'expiresAt',
        'token',
        'createdAt',
        'updatedAt',
        'ipAddress',
        'userAgent',
        'userId',
    ];

    protected $casts = [
        'expiresAt' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    /**
     * Get the user that owns the session
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    /**
     * Serialize to JSON (like YII)
     */
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'access_token' => $this->token,
            'user' => $this->user ? $this->user->toArray() : null,
            'expiresAt' => $this->expiresAt,
        ];
    }
}
