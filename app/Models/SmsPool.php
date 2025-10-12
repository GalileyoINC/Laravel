<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
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
        return $this->belongsTo(User::class, 'id_user');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_sms_pool');
    }

    public function phone_numbers()
    {
        return $this->belongsToMany(PhoneNumber::class, 'sms_pool_phone_number', 'id_sms_pool', 'id_phone_number')
            ->withPivot('id', 'id_user', 'number', 'is_satellite', 'status', 'sid', 'error', 'id_provider', 'type');
    }

    public function sms_pool_photos()
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_pool');
    }

    public function photos()
    {
        return $this->hasMany(SmsPoolPhoto::class, 'id_sms_pool');
    }

    public function reactions()
    {
        return $this->belongsToMany(Reaction::class, 'sms_pool_reaction', 'id_sms_pool', 'id_reaction')
            ->withPivot('id_user');
    }

    public function sms_shedules()
    {
        return $this->hasMany(SmsShedule::class, 'id_sms_pool');
    }

    public function user_point_histories()
    {
        return $this->hasMany(UserPointHistory::class, 'id_sms_pool');
    }
}
