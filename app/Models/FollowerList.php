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
 * Class FollowerList
 * 
 * @property int $id
 * @property int $id_user
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $token
 * @property string|null $description
 * @property string|null $image
 * @property bool $is_active
 * 
 * @property User $user
 * @property Collection|Follower[] $followers
 * @property Collection|Invite[] $invites
 * @property Collection|SmsShedule[] $sms_shedules
 *
 * @package App\Models
 */
class FollowerList extends Model
{
	use HasFactory;

	protected $table = 'follower_list';

	protected $casts = [
		'id_user' => 'int',
		'is_active' => 'bool'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'id_user',
		'name',
		'token',
		'description',
		'image',
		'is_active'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function followers()
	{
		return $this->hasMany(Follower::class, 'id_follower_list');
	}

	public function invites()
	{
		return $this->hasMany(Invite::class, 'id_follower_list');
	}

	public function sms_shedules()
	{
		return $this->hasMany(SmsShedule::class, 'id_follower_list');
	}
}
