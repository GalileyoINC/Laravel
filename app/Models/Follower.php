<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Follower
 * 
 * @property int $id
 * @property int $id_follower_list
 * @property int $id_user_leader
 * @property int $id_user_follower
 * @property bool|null $is_active
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property array|null $invite_settings
 * 
 * @property FollowerList $follower_list
 * @property User $user
 *
 * @package App\Models
 */
class Follower extends Model
{
	use HasFactory;

	protected $table = 'follower';

	protected $casts = [
		'id_follower_list' => 'int',
		'id_user_leader' => 'int',
		'id_user_follower' => 'int',
		'is_active' => 'bool',
		'invite_settings' => 'json'
	];

	protected $fillable = [
		'id_follower_list',
		'id_user_leader',
		'id_user_follower',
		'is_active',
		'invite_settings'
	];

	public function follower_list()
	{
		return $this->belongsTo(FollowerList::class, 'id_follower_list');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user_leader');
	}
}
