<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Database\Factories\SubscriptionFactory as RootSubscriptionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class Subscription
 *
 * @property int $id
 * @property int $id_subscription_category
 * @property string $name
 * @property string|null $title
 * @property int|null $position_no
 * @property string|null $description
 * @property string|null $rule
 * @property int|null $alert_type
 * @property int|null $is_active
 * @property int $is_hidden
 * @property float $percent
 * @property \Illuminate\Support\Carbon|null $sended_at
 * @property array<array-key, mixed>|null $params
 * @property bool|null $is_custom
 * @property int|null $id_influencer
 * @property float $price
 * @property int $bonus_point
 * @property string|null $token
 * @property string|null $ipfs_id
 * @property bool $is_public
 * @property bool $is_fake
 * @property int|null $type
 * @property bool $show_reactions
 * @property bool $show_comments
 * @property-read \App\Models\User\User|null $influencer
 * @property-read InfluencerPage|null $influencerPage
 * @property-read Collection<int, InfluencerPage> $influencer_pages
 * @property-read int|null $influencer_pages_count
 * @property-read Collection<int, \App\Models\Communication\SmsShedule> $sms_shedules
 * @property-read int|null $sms_shedules_count
 * @property-read SubscriptionCategory $subscription_category
 * @property-read \App\Models\User\User|null $user
 * @property-read Collection<int, \App\Models\User\User> $userSubscriptions
 * @property-read int|null $user_subscriptions_count
 * @property-read Collection<int, \App\Models\User\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\Subscription\SubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereAlertType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereBonusPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIdInfluencer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIdSubscriptionCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIpfsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIsCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIsFake($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIsHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePositionNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereSendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereShowComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereShowReactions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereType($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SubscriptionFactory>
 */
class Subscription extends Model
{
    use HasFactory; /** @phpstan-ignore-line */ /** @phpstan-ignore-line */ /** @phpstan-ignore-line */

    // Modes for statistics helpers
    public const ACTIVE_ONLY = 1;

    public $timestamps = false;

    protected $table = 'subscription';

    protected $casts = [
        'id_subscription_category' => 'int',
        'position_no' => 'int',
        'alert_type' => 'int',
        'is_active' => 'int',
        'is_hidden' => 'int',
        'percent' => 'float',
        'sended_at' => 'datetime',
        'params' => 'json',
        'is_custom' => 'bool',
        'id_influencer' => 'int',
        'price' => 'float',
        'bonus_point' => 'int',
        'is_public' => 'bool',
        'is_fake' => 'bool',
        'type' => 'int',
        'show_reactions' => 'bool',
        'show_comments' => 'bool',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_subscription_category',
        'name',
        'title',
        'position_no',
        'description',
        'rule',
        'alert_type',
        'is_active',
        'is_hidden',
        'percent',
        'sended_at',
        'params',
        'is_custom',
        'id_influencer',
        'price',
        'bonus_point',
        'token',
        'ipfs_id',
        'is_public',
        'is_fake',
        'type',
        'show_reactions',
        'show_comments',
    ];

    /**
     * Return all subscriptions as [id => label] for filter dropdowns.
     * Uses title if available, otherwise falls back to name.
     */
    /**
     * @return array<int, string>
     */
    public static function getAllAsArray(): array
    {
        return self::query()
            ->orderBy('id')
            ->get(['id', 'title', 'name'])
            ->mapWithKeys(function ($s) {
                $label = $s->title ?? $s->name ?? ('Subscription #'.$s->id);

                return [$s->id => $label];
            })
            ->toArray();
    }

    /**
     * Return subscriptions as array of ['id' => int, 'name' => string]
     * suitable for blade dropdowns expecting keys by name.
     */
    /**
     * @return list<array{id: int, name: string}>
     */
    public static function getForDropDown(): array
    {
        return self::query()
            ->orderBy('id')
            ->get(['id', 'title', 'name'])
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->title ?? $s->name ?? ('Subscription #'.$s->id),
            ])
            ->toArray();
    }

    /**
     * Get user counts per subscription.
     * Returns array of ['id_subscription' => int, 'cnt' => int].
     */
    /**
     * @return list<array{id_subscription: int, cnt: int}>
     */
    public static function getUserStatistics(int $mode = self::ACTIVE_ONLY): array
    {
        try {
            $rows = DB::table('user_subscription_address')
                ->join('user', 'user_subscription_address.id_user', '=', 'user.id')
                ->select('user_subscription_address.id_subscription as id_subscription', DB::raw('COUNT(*) as cnt'))
                ->when($mode === self::ACTIVE_ONLY, function ($q) {
                    $q->where('user.status', 1);
                })
                ->groupBy('user_subscription_address.id_subscription')
                ->get();

            return $rows->map(fn ($r) => [
                'id_subscription' => (int) $r->id_subscription,
                'cnt' => (int) $r->cnt,
            ])->toArray();
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Get the is_subscribed attribute (computed property)
     */
    public function getIsSubscribedAttribute(): bool
    {
        // This would typically check if the current user is subscribed to this subscription
        // For now, we'll return false as a default
        return false;
    }

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_influencer');
    }

    // Alias expected by controllers
    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function influencer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_influencer');
    }

    /**
     * @return BelongsTo<SubscriptionCategory, $this>
     */
    public function subscription_category(): BelongsTo
    {
        return $this->belongsTo(SubscriptionCategory::class, 'id_subscription_category');
    }

    /**
     * @return HasMany<InfluencerPage, $this>
     */
    public function influencer_pages(): HasMany
    {
        return $this->hasMany(InfluencerPage::class, 'id_subscription');
    }

    // Alias expected by controllers
    /**
     * @return HasOne<InfluencerPage, $this>
     */
    public function influencerPage(): HasOne
    {
        return $this->hasOne(InfluencerPage::class, 'id_subscription');
    }

    /**
     * @return HasMany<\App\Models\Communication\SmsShedule, $this>
     */
    public function sms_shedules(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\SmsShedule::class, 'id_subscription');
    }

    /**
     * @return BelongsToMany<\App\Models\User\User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User\User::class, 'user_subscription_address', 'id_subscription', 'id_user')
            ->withPivot('id', 'zip');
    }

    // Alias expected by controllers for counting/queries
    /**
     * @return BelongsToMany<\App\Models\User\User, $this>
     */
    public function userSubscriptions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User\User::class, 'user_subscription_address', 'id_subscription', 'id_user')
            ->withPivot('id', 'zip');
    }

    protected static function newFactory(): RootSubscriptionFactory
    {
        return RootSubscriptionFactory::new();
    }
}
