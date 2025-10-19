<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Database\Factories\SmsPoolFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Throwable;

/**
 * Class SmsPool
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_staff
 * @property int|null $id_subscription
 * @property int|null $id_follower_list
 * @property int|null $purpose
 * @property int $status
 * @property string $body
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $sms_provider
 * @property int|null $id_assistant
 * @property string|null $short_body
 * @property string|null $url
 * @property bool $is_ban
 * @property-read \App\Models\User\User|null $assistant
 * @property-read Collection<int, \App\Models\Bookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @property-read Collection<int, \App\Models\Content\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Subscription\FollowerList|null $followerList
 * @property-read int|null $comment_quantity
 * @property-read int|null $emergency_level
 * @property-read string|null $image
 * @property-read bool|null $is_bookmarked
 * @property-read bool|null $is_liked
 * @property-read bool|null $is_owner
 * @property-read array|null $images
 * @property-read array|null $reactions
 * @property-read array|null $user_info
 * @property-read array|null $images
 * @property-read array|null $reactions
 * @property-read array|null $user_info
 * @property-read bool|null $is_subscribed
 * @property-read string|null $location
 * @property-read float|null $percent
 * @property-read float|null $price
 * @property-read string|null $subtitle
 * @property-read string|null $title
 * @property-read int|null $type
 * @property-read Collection<int, SmsPoolPhoto> $images
 * @property-read int|null $images_count
 * @property-read Collection<int, \App\Models\Device\PhoneNumber> $phone_numbers
 * @property-read int|null $phone_numbers_count
 * @property-read Collection<int, SmsPoolPhoto> $photos
 * @property-read int|null $photos_count
 * @property-read Collection<int, \App\Models\Content\Reaction> $reactions
 * @property-read int|null $reactions_count
 * @property-read Collection<int, SmsPoolPhoto> $sms_pool_photos
 * @property-read int|null $sms_pool_photos_count
 * @property-read Collection<int, SmsShedule> $sms_shedules
 * @property-read int|null $sms_shedules_count
 * @property-read \App\Models\System\Staff|null $staff
 * @property-read \App\Models\Subscription\Subscription|null $subscription
 * @property-read \App\Models\User\User|null $user
 * @property-read Collection<int, \App\Models\User\UserPointHistory> $user_point_histories
 * @property-read int|null $user_point_histories_count
 *
 * @method static \Database\Factories\SmsPoolFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereIdAssistant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereIdFollowerList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereIdStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereIsBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereShortBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereSmsProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPool whereUrl($value)
 *
 * @mixin \Eloquent
 */
class SmsPool extends Model
{
    use HasFactory;

    protected $table = 'sms_pool';

    protected $casts = [
        'id_user' => 'int',
        'id_staff' => 'int',
        'id_subscription' => 'int',
        'id_follower_list' => 'int',
        'purpose' => 'int',
        'status' => 'int',
        'id_assistant' => 'int',
        'is_ban' => 'bool',
    ];

    protected $fillable = [
        'id_user',
        'id_staff',
        'id_subscription',
        'id_follower_list',
        'purpose',
        'status',
        'body',
        'sms_provider',
        'id_assistant',
        'short_body',
        'url',
        'is_ban',
    ];

    /**
     * Return available purposes for dropdowns and labeling.
     * Falls back to discovered purpose codes in DB with generic labels.
     *
     * @return array<int, string>
     */
    public static function getPurposes(): array
    {
        $defaults = [
            0 => 'General',
            1 => 'Subscription',
            2 => 'Follower List',
            3 => 'Alert',
            4 => 'Marketing',
        ];

        try {
            $codes = static::query()->select('purpose')->distinct()->pluck('purpose')->filter(fn ($v) => $v !== null)->all();

            $map = $defaults;
            foreach ($codes as $code) {
                if (! array_key_exists($code, $map)) {
                    $map[$code] = 'Purpose '.$code;
                }
            }

            ksort($map);

            return $map;
        } catch (Throwable) {
            return $defaults;
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\System\Staff::class, 'id_staff');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(\App\Models\Content\Comment::class, 'id_sms_pool');
    }

    public function phone_numbers(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Device\PhoneNumber::class, 'sms_pool_phone_number', 'id_sms_pool', 'id_phone_number')
            ->withPivot('id', 'id_user', 'number', 'is_satellite', 'status', 'sid', 'error', 'id_provider', 'type');
    }

    public function sms_pool_photos(): HasMany
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_pool');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(\App\Models\Bookmark::class, 'post_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_pool');
    }

    public function reactions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Content\Reaction::class, 'sms_pool_reaction', 'id_sms_pool', 'id_reaction')
            ->withPivot('id_user');
    }

    public function sms_shedules(): HasMany
    {
        return $this->hasMany(SmsShedule::class, 'id_sms_pool');
    }

    public function user_point_histories(): HasMany
    {
        return $this->hasMany(\App\Models\User\UserPointHistory::class, 'id_sms_pool');
    }

    public function followerList(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\FollowerList::class, 'id_follower_list');
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_assistant');
    }

    /**
     * Get images relationship (alias for photos)
     */
    public function images(): HasMany
    {
        return $this->photos();
    }

    /**
     * Get computed properties for frontend
     */
    public function getIsLikedAttribute(): ?bool
    {
        return $this->attributes['is_liked'] ?? null;
    }

    public function getIsBookmarkedAttribute(): ?bool
    {
        return $this->attributes['is_bookmarked'] ?? null;
    }

    public function getIsSubscribedAttribute(): ?bool
    {
        return $this->attributes['is_subscribed'] ?? null;
    }

    public function getIsOwnerAttribute(): ?bool
    {
        return $this->attributes['is_owner'] ?? null;
    }

    public function getCommentQuantityAttribute(): ?int
    {
        return $this->attributes['comment_quantity'] ?? null;
    }

    public function getPercentAttribute(): ?float
    {
        return $this->attributes['percent'] ?? null;
    }

    public function getPriceAttribute(): ?float
    {
        return $this->attributes['price'] ?? null;
    }

    public function getTypeAttribute(): ?int
    {
        return $this->attributes['type'] ?? null;
    }

    public function getTitleAttribute(): ?string
    {
        return $this->attributes['title'] ?? null;
    }

    public function getSubtitleAttribute(): ?string
    {
        return $this->attributes['subtitle'] ?? null;
    }

    public function getImageAttribute(): ?string
    {
        return $this->attributes['image'] ?? null;
    }

    public function getEmergencyLevelAttribute(): ?int
    {
        return $this->attributes['emergency_level'] ?? null;
    }

    public function getLocationAttribute(): ?string
    {
        return $this->attributes['location'] ?? null;
    }

    protected static function newFactory(): SmsPoolFactory
    {
        return SmsPoolFactory::new();
    }
}
