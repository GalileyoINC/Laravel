<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use App\Models\Communication\SmsShedule;
use App\Models\User\Invite;
use App\Models\User\User;
use Database\Factories\FollowerListFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class FollowerList
 *
 * @property int $id
 * @property int $id_user
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $token
 * @property string|null $description
 * @property string|null $image
 * @property bool $is_active
 * @property-read Collection<int, Follower> $followers
 * @property-read int|null $followers_count
 * @property-read string|null $image_path
 * @property-read string|null $title
 * @property-read Collection<int, Invite> $invites
 * @property-read int|null $invites_count
 * @property-read Collection<int, SmsShedule> $sms_shedules
 * @property-read int|null $sms_shedules_count
 * @property-read User $user
 *
 * @method static \Database\Factories\FollowerListFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowerList whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FollowerList extends Model
{
    use HasFactory;

    protected $table = 'follower_list';

    protected $casts = [
        'id_user' => 'int',
        'is_active' => 'bool',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_user',
        'name',
        'token',
        'description',
        'image',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'id_follower_list');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'id_follower_list');
    }

    public function sms_shedules(): HasMany
    {
        return $this->hasMany(SmsShedule::class, 'id_follower_list');
    }

    /**
     * Get title attribute (alias for name)
     */
    public function getTitleAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    /**
     * Get image_path attribute (alias for image)
     */
    public function getImagePathAttribute(): ?string
    {
        return $this->attributes['image'] ?? null;
    }

    protected static function newFactory()
    {
        return FollowerListFactory::new();
    }
}
