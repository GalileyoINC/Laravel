<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property Carbon|null $sended_at
 * @property array|null $params
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
 * @property User|null $user
 * @property SubscriptionCategory $subscription_category
 * @property Collection|InfluencerPage[] $influencer_pages
 * @property Collection|SmsShedule[] $sms_shedules
 * @property Collection|User[] $users
 */
class Subscription extends Model
{
    use HasFactory;

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
    public static function getForDropDown(): array
    {
        return self::query()
            ->orderBy('id')
            ->get(['id', 'title', 'name'])
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->title ?? $s->name ?? ('Subscription #'.$s->id),
                ];
            })
            ->toArray();
    }

    /**
     * Get user counts per subscription.
     * Returns array of ['id_subscription' => int, 'cnt' => int].
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

            return $rows->map(function ($r) {
                return [
                    'id_subscription' => (int) $r->id_subscription,
                    'cnt' => (int) $r->cnt,
                ];
            })->toArray();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_influencer');
    }

    // Alias expected by controllers
    public function influencer()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_influencer');
    }

    public function subscription_category()
    {
        return $this->belongsTo(SubscriptionCategory::class, 'id_subscription_category');
    }

    public function influencer_pages()
    {
        return $this->hasMany(InfluencerPage::class, 'id_subscription');
    }

    // Alias expected by controllers
    public function influencerPage()
    {
        return $this->hasOne(InfluencerPage::class, 'id_subscription');
    }

    public function sms_shedules()
    {
        return $this->hasMany(\App\Models\Communication\SmsShedule::class, 'id_subscription');
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User\User::class, 'user_subscription_address', 'id_subscription', 'id_user')
            ->withPivot('id', 'zip');
    }

    // Alias expected by controllers for counting/queries
    public function userSubscriptions()
    {
        return $this->belongsToMany(\App\Models\User\User::class, 'user_subscription_address', 'id_subscription', 'id_user')
            ->withPivot('id', 'zip');
    }
}
