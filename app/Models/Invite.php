<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Invite
 * 
 * @property int $id
 * @property int $id_user
 * @property int $id_follower_list
 * @property string $email
 * @property string|null $name
 * @property string|null $phone_number
 * @property string|null $token
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property FollowerList $follower_list
 * @property User $user
 *
 * @package App\Models
 */
class Invite extends Model
{
	use HasFactory;

	protected $table = 'invite';

	protected $casts = [
		'id_user' => 'int',
		'id_follower_list' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'id_user',
		'id_follower_list',
		'email',
		'name',
		'phone_number',
		'token'
	];

	public function follower_list()
	{
		return $this->belongsTo(FollowerList::class, 'id_follower_list');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
