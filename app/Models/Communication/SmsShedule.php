<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property Carbon $sended_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $id_assistant
 * @property string|null $short_body
 * @property string|null $url
 * @property User|null $user
 * @property FollowerList|null $follower_list
 * @property SmsPool|null $sms_pool
 * @property Staff|null $staff
 * @property Subscription|null $subscription
 * @property Collection|SmsPoolPhoto[] $sms_pool_photos
 */
class SmsShedule extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function follower_list()
    {
        return $this->belongsTo(\App\Models\Subscription\FollowerList::class, 'id_follower_list');
    }

    // Alias expected by controllers
    public function followerList()
    {
        return $this->belongsTo(\App\Models\Subscription\FollowerList::class, 'id_follower_list');
    }

    public function sms_pool()
    {
        return $this->belongsTo(\App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    // Alias expected by controllers
    public function smsPool()
    {
        return $this->belongsTo(\App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\System\Staff::class, 'id_staff');
    }

    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    public function sms_pool_photos()
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_shedule');
    }

    /**
     * Provide schedule statuses for filters and labeling.
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
            $codes = static::query()->select('status')->distinct()->pluck('status')->filter(function ($v) {
                return $v !== null;
            })->all();

            $map = $defaults;
            foreach ($codes as $code) {
                if (!array_key_exists($code, $map)) {
                    $map[$code] = 'Status '.$code;
                }
            }

            ksort($map);
            return $map;
        } catch (\Throwable) {
            return $defaults;
        }
    }
}
