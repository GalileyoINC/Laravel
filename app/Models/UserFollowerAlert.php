<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UserFollowerAlert
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $total
 * @property int|null $used
 * @property Carbon $begin_at
 * @property Carbon|null $end_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class UserFollowerAlert extends Model
{
	use HasFactory;

	protected $table = 'user_follower_alert';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'total' => 'int',
		'used' => 'int',
		'begin_at' => 'datetime',
		'end_at' => 'datetime'
	];

	protected $fillable = [
		'id_user',
		'total',
		'used',
		'begin_at',
		'end_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
