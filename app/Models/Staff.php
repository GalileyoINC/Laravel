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
 * Class Staff
 * 
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property int $role
 * @property int $status
 * @property int|null $is_superlogin
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|SmsPool[] $sms_pools
 * @property Collection|SmsShedule[] $sms_shedules
 *
 * @package App\Models
 */
class Staff extends Model
{
	use HasFactory;

	protected $table = 'staff';

	protected $casts = [
		'role' => 'int',
		'status' => 'int',
		'is_superlogin' => 'int'
	];

	protected $hidden = [
		'password_reset_token'
	];

	protected $fillable = [
		'username',
		'email',
		'auth_key',
		'password_hash',
		'password_reset_token',
		'role',
		'status',
		'is_superlogin'
	];

	public function sms_pools()
	{
		return $this->hasMany(SmsPool::class, 'id_staff');
	}

	public function sms_shedules()
	{
		return $this->hasMany(SmsShedule::class, 'id_staff');
	}
}
