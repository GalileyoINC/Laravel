<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Carbon\Carbon;
use Database\Factories\SmsPoolFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $sms_provider
 * @property int|null $id_assistant
 * @property string|null $short_body
 * @property string|null $url
 * @property bool $is_ban
 * @property User|null $user
 * @property Staff|null $staff
 * @property Collection|Comment[] $comments
 * @property Collection|PhoneNumber[] $phone_numbers
 * @property Collection|SmsPoolPhoto[] $sms_pool_photos
 * @property Collection|Reaction[] $reactions
 * @property Collection|SmsShedule[] $sms_shedules
 * @property Collection|UserPointHistory[] $user_point_histories
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

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\System\Staff::class, 'id_staff');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Content\Comment::class, 'id_sms_pool');
    }

    public function phone_numbers()
    {
        return $this->belongsToMany(\App\Models\Device\PhoneNumber::class, 'sms_pool_phone_number', 'id_sms_pool', 'id_phone_number')
            ->withPivot('id', 'id_user', 'number', 'is_satellite', 'status', 'sid', 'error', 'id_provider', 'type');
    }

    public function sms_pool_photos()
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_pool');
    }

    public function bookmarks()
    {
        return $this->hasMany(\App\Models\Bookmark::class, 'post_id');
    }

    public function photos()
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_pool');
    }

    public function reactions()
    {
        return $this->belongsToMany(\App\Models\Content\Reaction::class, 'sms_pool_reaction', 'id_sms_pool', 'id_reaction')
            ->withPivot('id_user');
    }

    public function sms_shedules()
    {
        return $this->hasMany(\App\Models\Communication\SmsShedule::class, 'id_sms_pool');
    }

    public function user_point_histories()
    {
        return $this->hasMany(\App\Models\User\UserPointHistory::class, 'id_sms_pool');
    }

    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription\Subscription::class, 'id_subscription');
    }

    /**
     * Return available purposes for dropdowns and labeling.
     * Falls back to discovered purpose codes in DB with generic labels.
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
            $codes = static::query()->select('purpose')->distinct()->pluck('purpose')->filter(function ($v) {
                return $v !== null;
            })->all();

            $map = $defaults;
            foreach ($codes as $code) {
                if (!array_key_exists($code, $map)) {
                    $map[$code] = 'Purpose '.$code;
                }
            }

            ksort($map);
            return $map;
        } catch (\Throwable) {
            return $defaults;
        }
    }

    protected static function newFactory()
    {
        return SmsPoolFactory::new();
    }
}
