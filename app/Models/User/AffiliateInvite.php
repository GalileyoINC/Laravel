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
 * Class AffiliateInvite
 *
 * @property int $id
 * @property int $id_user
 * @property string $email
 * @property string|null $phone_number
 * @property string|null $token
 * @property array<array-key, mixed>|null $params
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffiliateInvite whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AffiliateInviteFactory>
 */
class AffiliateInvite extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'affiliate_invite';

    protected $casts = [
        'id_user' => 'int',
        'params' => 'json',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_user',
        'email',
        'phone_number',
        'token',
        'params',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
