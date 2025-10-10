<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
 * 
 * @property User|null $user
 * @property FollowerList|null $follower_list
 * @property SmsPool|null $sms_pool
 * @property Staff|null $staff
 * @property Subscription|null $subscription
 * @property Collection|SmsPoolPhoto[] $sms_pool_photos
 *
 * @package App\Models
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
		'id_assistant' => 'int'
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
		'url'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function follower_list()
	{
		return $this->belongsTo(FollowerList::class, 'id_follower_list');
	}

	public function sms_pool()
	{
		return $this->belongsTo(SmsPool::class, 'id_sms_pool');
	}

	public function staff()
	{
		return $this->belongsTo(Staff::class, 'id_staff');
	}

	public function subscription()
	{
		return $this->belongsTo(Subscription::class, 'id_subscription');
	}

	public function sms_pool_photos()
	{
		return $this->hasMany(SmsPoolPhoto::class, 'id_sms_shedule');
	}
}
