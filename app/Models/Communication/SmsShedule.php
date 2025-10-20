<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Throwable;

/**
 * Class SmsShedule
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_staff
 * @property int|null $id_subscription
 * @property int|null $id_follower_list
 * @property int|null $id_sms_pool
 * @property int|null $purpose
 * @property int|null $status
 * @property string $body
 * @property \Illuminate\Support\Carbon $sended_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_assistant
 * @property string|null $short_body
 * @property string|null $url
 * @property-read \App\Models\Subscription\FollowerList|null $followerList
 * @property-read \App\Models\Subscription\FollowerList|null $follower_list
 * @property-read SmsPool|null $smsPool
 * @property-read SmsPool|null $sms_pool
 * @property-read Collection<int, SmsPoolPhoto> $sms_pool_photos
 * @property-read int|null $sms_pool_photos_count
 * @property-read \App\Models\System\Staff|null $staff
 * @property-read \App\Models\Subscription\Subscription|null $subscription
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereIdAssistant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereIdFollowerList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereIdSmsPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereIdStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereSendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereShortBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsShedule whereUrl($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationSmsSheduleFactory>
 */
class SmsShedule extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'sms_shedule';

    protected $casts = [
        'id_user' => 'int',
        'id_staff' => 'int',
        'id_subscription' => 'int',
        'id_follower_list' => 'int',
        'id_sms_pool' => 'int',
        'purpose' => 'int',
        'status' => 'int',
        'sended_at' => 'datetime',
        'id_assistant' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_staff',
        'id_subscription',
        'id_follower_list',
        'id_sms_pool',
        'purpose',
        'status',
        'body',
        'sended_at',
        'id_assistant',
        'short_body',
        'url',
    ];

    /**
     * Provide schedule statuses for filters and labeling.
     */
    /**
     * @return array<int, string>
     */
    public static function getStatuses(): array
    {
        // Default status map (adjust as needed if you have canonical values)
        $defaults = [
            0 => 'Pending',
            1 => 'Queued',
            2 => 'Sent',
            3 => 'Failed',
            4 => 'Canceled',
        ];

        try {
            $codes = static::query()->select('status')->distinct()->pluck('status')->filter(fn ($v) => $v !== null)->all();

            $map = $defaults;
            foreach ($codes as $code) {
                if (! array_key_exists($code, $map)) {
                    $map[$code] = 'Status '.$code;
                }
            }

            ksort($map);

            return $map;
        } catch (Throwable) {
            return $defaults;
        }
    }

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return BelongsTo<\App\Models\Subscription\FollowerList, $this>
     */
    public function follower_list(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\FollowerList::class, 'id_follower_list');
    }

    // Alias expected by controllers
    /**
     * @return BelongsTo<\App\Models\Subscription\FollowerList, $this>
     */
    public function followerList(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\FollowerList::class, 'id_follower_list');
    }

    /**
     * @return BelongsTo<SmsPool, $this>
     */
    public function sms_pool(): BelongsTo
    {
        return $this->belongsTo(SmsPool::class, 'id_sms_pool');
    }

    // Alias expected by controllers
    /**
     * @return BelongsTo<SmsPool, $this>
     */
    public function smsPool(): BelongsTo
    {
        return $this->belongsTo(SmsPool::class, 'id_sms_pool');
    }

    /**
     * @return BelongsTo<\App\Models\System\Staff, $this>
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\System\Staff::class, 'id_staff');
    }

    /**
     * @return BelongsTo<\App\Models\Subscription\Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    /**
     * @return HasMany<SmsPoolPhoto, $this>
     */
    public function sms_pool_photos(): HasMany
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_shedule');
    }
}
